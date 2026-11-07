<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Translates a constraint StructureDefinition into the validation attributes that enforce it.
 *
 * This is the single translation of profile rules: the code generator renders its output as
 * attributes on generated profile classes, and runtime validation instantiates the same output,
 * so a generated profile and a profile loaded at runtime cannot disagree.
 *
 * Every attribute carries `groups: [<profile URL>]`, so its rules fire only when that profile is
 * requested. Only this StructureDefinition's own rules are compiled: a parent profile's rules come
 * from compiling the parent under its own URL, never from restating them here, which would report
 * each violation twice.
 *
 * Output order is part of the contract: generated classes must stay byte-identical, so attributes
 * come out as differential constraints, then must-support, then slices, then snapshot obligations.
 *
 * @author Ardenexal
 */
final class StructureDefinitionCompiler
{
    /**
     * Compile one StructureDefinition into its attributes and any diagnostics.
     *
     * @param array<string, mixed> $structureDefinition StructureDefinition with derivation=constraint
     */
    public function compile(array $structureDefinition): CompiledProfile
    {
        $profileUrl  = (string) ($structureDefinition['url'] ?? '');
        $diagnostics = [];

        $attributes = [
            ...self::differentialConstraints($structureDefinition, $profileUrl),
            ...self::differentialMustSupport($structureDefinition, $profileUrl),
            ...self::differentialSliceConstraints($structureDefinition, $profileUrl, $diagnostics),
            ...self::snapshotObligations($structureDefinition, $profileUrl),
        ];

        return new CompiledProfile($profileUrl, $attributes, $diagnostics);
    }

    /**
     * One FHIRProfileConstraint per rule on each differential element that carries a cardinality
     * (min/max), fixed[x], or pattern[x] constraint.
     *
     * Skipped elements:
     *  - Root element (path has no '.'): it is the resource or type itself, not a property
     *  - Elements with contentReference: the constraint lives on the referenced element
     *  - Slice elements and their children (see differentialPropertyPath()): their `path` is
     *    unsliced, so a plain constraint would apply to every item of the sliced element. Slice
     *    rules come from differentialSliceConstraints() instead. Choice type slices
     *    ("value[x]:valueQuantity") are kept, on the variant's path ("valueQuantity").
     *
     * @param array<string, mixed> $structureDefinition
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialConstraints(array $structureDefinition, string $profileUrl): array
    {
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $element) {
            $path = (string) ($element['path'] ?? '');

            if (!str_contains($path, '.')) {
                continue;
            }

            if (ElementDefinitionReader::hasContentReference($element)) {
                continue;
            }

            $propertyPath = self::differentialPropertyPath($element);
            if ($propertyPath === null) {
                continue;
            }

            foreach (self::elementRules($element) as $rule) {
                $attributes[] = new CompiledAttribute(FHIRProfileConstraint::class, [
                    'path'       => $propertyPath,
                    'constraint' => $rule['constraint'],
                    'options'    => $rule['options'],
                    'groups'     => [$profileUrl],
                ]);
            }
        }

        return $attributes;
    }

    /**
     * One FHIRProfileMustSupport per differential element that declares mustSupport=true.
     *
     * Profile classes cannot re-declare inherited properties, so must-support is carried at the
     * class level as pure metadata (no Symfony Validator involvement).
     *
     * @param array<string, mixed> $structureDefinition
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialMustSupport(array $structureDefinition, string $profileUrl): array
    {
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $element) {
            if (($element['mustSupport'] ?? false) !== true) {
                continue;
            }

            $path = (string) ($element['path'] ?? '');

            if (!str_contains($path, '.')) {
                continue;
            }

            // Must-support on one slice does not make the unsliced path must-support
            $propertyPath = self::differentialPropertyPath($element);
            if ($propertyPath === null) {
                continue;
            }

            $attributes[] = new CompiledAttribute(FHIRProfileMustSupport::class, [
                'path'   => $propertyPath,
                'groups' => [$profileUrl],
            ]);
        }

        return $attributes;
    }

    /**
     * FHIRSlicingRules and FHIRSliceConstraint attributes for the slices this profile declares or
     * constrains.
     *
     * Every slice element names its slice in `id` ("Observation.component:SystolicBP"), so slices are
     * found by id: a sliced element is one that a differential id slices, or one that declares
     * `slicing`. Each fact comes from the source that is authoritative for it:
     *
     *  - Slicing (discriminator, rules): this differential when it declares it, else the snapshot.
     *    Published IGs rarely restate a parent's slicing, so a profile that adds a slice to it, or
     *    narrows an inherited slice, finds the slicing only in its snapshot.
     *  - Discriminator value: the snapshot first, in snapshot order, so an inherited slice keeps its
     *    parent's value rather than one the profile adds beneath it; else the differential.
     *  - Cardinality and rules: this differential only. A slice the profile touches only through its
     *    children gets 0..*: the parent profile already checks the parent's cardinality and rules
     *    under the parent's group, and restating them would report each violation twice.
     *
     * FHIRSlicingRules is compiled only for slicing declared here. An inherited closed slicing
     * checked in this profile's group, which holds only this profile's slices, would reject the
     * items that match the parent's.
     *
     * Slicing beneath a slice ("component:SystolicBP.code.coding:snomedSBP") travels in that slice's
     * `rules` as nested slice definitions, which the validator applies within each matching item.
     *
     * Limitations:
     *  - Only the first discriminator of a composite discriminator is used.
     *  - A slice whose discriminator cannot be matched (types 'type' and 'profile', `$this`, or a
     *    value or pattern discriminator with no value found) gets no minimum, since it could only fail.
     *  - Choice type slices ("value[x]:valueQuantity") are left to the plain constraints, which put
     *    them on the variant path. Reslices ("component:a/b") are not compiled.
     *  - A slice on slicing found in neither the differential nor the snapshot (a StructureDefinition
     *    built without a snapshot that slices inherited slicing) cannot be compiled; it is reported as
     *    a diagnostic rather than dropped silently.
     *
     * @param array<string, mixed>     $structureDefinition
     * @param list<CompilerDiagnostic> $diagnostics         Receives one entry per slicing that cannot be found
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialSliceConstraints(array $structureDefinition, string $profileUrl, array &$diagnostics): array
    {
        $diff       = self::differentialElements($structureDefinition);
        $snapshot   = self::snapshotElements($structureDefinition);
        $attributes = [];

        foreach (self::allSlicedElementIds($diff) as $slicedId) {
            if (self::slicingFor($slicedId, $diff, $snapshot) === null) {
                $diagnostics[] = new CompilerDiagnostic(
                    "Profile {$profileUrl} slices {$slicedId}, but neither its differential nor a snapshot defines that slicing; its slices are not enforced",
                    $slicedId,
                    ['profileUrl' => $profileUrl],
                );
            }
        }

        foreach (self::slicedElementIds($diff, '') as $slicedId) {
            $dotPos  = strpos($slicedId, '.');
            $slicing = self::slicingFor($slicedId, $diff, $snapshot);
            if ($dotPos === false || $slicing === null) {
                continue;
            }

            $slices = self::sliceDefinitions($slicedId, $slicing, $diff, $snapshot);
            if ($slices === []) {
                continue;
            }

            // "Observation.code.coding" → "code.coding"
            $propertyPath = self::rewriteChoiceTypeSlices(substr($slicedId, $dotPos + 1));

            if ($slicing['declaredHere']) {
                $attributes[] = new CompiledAttribute(FHIRSlicingRules::class, [
                    'property' => $propertyPath,
                    'rules'    => (string) ($slicing['definition']['rules'] ?? 'open'),
                    'groups'   => [$profileUrl],
                ]);
            }

            foreach ($slices as $slice) {
                $attributes[] = new CompiledAttribute(FHIRSliceConstraint::class, [
                    'property' => $propertyPath,
                    ...$slice,
                    'groups'   => [$profileUrl],
                ]);
            }
        }

        return $attributes;
    }

    /**
     * One FHIRProfileObligation per obligation extension on a snapshot element
     * (http://hl7.org/fhir/StructureDefinition/obligation).
     *
     * Obligations appear in snapshot (not differential) elements per the FHIR specification.
     *
     * @param array<string, mixed> $structureDefinition
     *
     * @return list<CompiledAttribute>
     */
    private static function snapshotObligations(array $structureDefinition, string $profileUrl): array
    {
        /** @var array<int, array<string, mixed>> $elements */
        $elements = $structureDefinition['snapshot']['element'] ?? [];

        if ($elements === []) {
            return [];
        }

        $reader     = new ObligationExtensionReader();
        $attributes = [];

        foreach ($elements as $element) {
            $path = (string) ($element['path'] ?? '');

            if (!str_contains($path, '.')) {
                continue;
            }

            if (ElementDefinitionReader::hasContentReference($element)) {
                continue;
            }

            /** @var array<int, array<string, mixed>> $extensions */
            $extensions  = $element['extension'] ?? [];
            $obligations = $reader->parse($extensions);

            if ($obligations === []) {
                continue;
            }

            $dotPos       = strpos($path, '.');
            $propertyPath = $dotPos !== false ? substr($path, $dotPos + 1) : $path;

            foreach ($obligations as $obligation) {
                $args = [
                    'path'   => $propertyPath,
                    'code'   => $obligation['code'],
                    'groups' => [$profileUrl],
                ];

                if ($obligation['actor'] !== null) {
                    $args['actor'] = $obligation['actor'];
                }

                if ($obligation['filter'] !== null) {
                    $args['filter'] = $obligation['filter'];
                }

                $attributes[] = new CompiledAttribute(FHIRProfileObligation::class, $args);
            }
        }

        return $attributes;
    }

    /**
     * The constraints one differential element places on its own path: cardinality (Count) when
     * min > 0 or max is bounded, a scalar fixed[x] (complex fixed[x] needs profile resolution), and
     * an array pattern[x] (scalar patterns use value matching).
     *
     * @param array<string, mixed> $element
     *
     * @return list<array{constraint: class-string<Count|FHIRFixedValue|FHIRPatternValue>, options: array<string, mixed>}>
     */
    private static function elementRules(array $element): array
    {
        $rules = [];

        $min = (int) ($element['min'] ?? 0);
        $max = (string) ($element['max'] ?? '*');

        $countOptions = [];
        if ($min > 0) {
            $countOptions['min'] = $min;
        }
        if (is_numeric($max)) {
            $countOptions['max'] = (int) $max;
        }
        if ($countOptions !== []) {
            $rules[] = ['constraint' => Count::class, 'options' => $countOptions];
        }

        $fixedField = ElementDefinitionReader::extractPolymorphicField($element, 'fixed');
        if ($fixedField !== null && is_scalar($fixedField['value'])) {
            $rules[] = ['constraint' => FHIRFixedValue::class, 'options' => ['value' => $fixedField['value']]];
        }

        $patternField = ElementDefinitionReader::extractPolymorphicField($element, 'pattern');
        if ($patternField !== null && is_array($patternField['value'])) {
            $rules[] = ['constraint' => FHIRPatternValue::class, 'options' => ['pattern' => $patternField['value']]];
        }

        return $rules;
    }

    /**
     * Arguments for one #[FHIRSliceConstraint] per slice of a sliced element, without `property`
     * and `groups`.
     *
     * The slices are those this differential declares or reaches into. When the profile restates
     * the slicing as closed or openAtEnd, the snapshot's slices join them as bare 0..* members: the
     * FHIRSlicingRules compiled in this profile's group judges every item against the slices in that
     * group, so an item matching only a parent's slice would otherwise be rejected.
     *
     * @param string                                                      $slicedId Id of the sliced element, e.g. 'Observation.component'
     * @param array{definition: array<string, mixed>, declaredHere: bool} $slicing  Its slicing, from slicingFor()
     * @param array<string, array<string, mixed>>                         $diff     Differential elements by id
     * @param array<string, array<string, mixed>>                         $snapshot Snapshot elements by id
     *
     * @return list<array<string, mixed>>
     */
    private static function sliceDefinitions(string $slicedId, array $slicing, array $diff, array $snapshot): array
    {
        $definition     = $slicing['definition'];
        $discriminators = is_array($definition['discriminator'] ?? null) ? array_values($definition['discriminator']) : [];
        $first          = is_array($discriminators[0] ?? null) ? $discriminators[0] : [];
        $discType       = (string) ($first['type'] ?? 'value');
        $discPath       = (string) ($first['path'] ?? '');

        $names = self::sliceNames($slicedId, $diff);
        if ($names !== [] && $slicing['declaredHere'] && ($definition['rules'] ?? 'open') !== 'open') {
            $names = array_values(array_unique([...$names, ...self::sliceNames($slicedId, $snapshot)]));
        }

        $definitions = [];
        foreach ($names as $orderedIndex => $sliceName) {
            $sliceId   = "{$slicedId}:{$sliceName}";
            $header    = $diff[$sliceId] ?? [];
            $isDefault = $sliceName === '@default';
            $value     = self::discriminatorValue($sliceId, $discType, $discPath, $diff, $snapshot);
            $max       = (string) ($header['max'] ?? '*');

            // An item can only join a slice its discriminator can match, so a minimum on any other
            // slice could only fail. The default slice takes the items no named slice matched.
            $matchable = $isDefault
                || $discType === 'exists'
                || (in_array($discType, ['value', 'pattern'], true) && $value !== null);

            $definition = [
                'sliceName'         => $sliceName,
                'min'               => $matchable ? (int) ($header['min'] ?? 0) : 0,
                'max'               => is_numeric($max) ? (int) $max : $max,
                'discriminatorType' => $discType,
                'discriminatorPath' => $discPath,
            ];

            if ($value !== null) {
                $definition['discriminatorValue'] = $value;
            }

            if ($isDefault) {
                $definition['isDefault'] = true;
            }

            $definition['orderedIndex'] = $orderedIndex;

            $rules = self::sliceRules($sliceId, $diff, $snapshot);
            if ($rules !== []) {
                $definition['rules'] = $rules;
            }

            $definitions[] = $definition;
        }

        return $definitions;
    }

    /**
     * The rules this differential places beneath one slice, with paths relative to a slice item.
     *
     * A sliced element beneath the slice contributes one nested FHIRSliceConstraint definition per
     * slice, plus its FHIRSlicingRules when the slicing is declared here; the validator applies them
     * to that element's items within each item matching this slice.
     *
     * @param string                              $sliceId  Id of the slice, e.g. 'Observation.component:SystolicBP'
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     *
     * @return list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function sliceRules(string $sliceId, array $diff, array $snapshot): array
    {
        $prefix = $sliceId . '.';
        $rules  = [];

        foreach ($diff as $id => $element) {
            if (!str_starts_with($id, $prefix) || ElementDefinitionReader::hasContentReference($element)) {
                continue;
            }

            // Elements beneath a nested slice belong to that slice's own definition, below
            $relative = self::rewriteChoiceTypeSlices(substr($id, strlen($prefix)));
            if (str_contains($relative, ':')) {
                continue;
            }

            foreach (self::elementRules($element) as $rule) {
                $rules[] = ['path' => $relative, ...$rule];
            }
        }

        foreach (self::slicedElementIds($diff, $sliceId) as $nestedId) {
            $slicing = self::slicingFor($nestedId, $diff, $snapshot);
            if ($slicing === null) {
                continue;
            }

            // As at the top level, a slicing with no slices to check compiles to nothing: closed rules
            // with no slice beside them would reject every item
            $definitions = self::sliceDefinitions($nestedId, $slicing, $diff, $snapshot);
            if ($definitions === []) {
                continue;
            }

            $relative = self::rewriteChoiceTypeSlices(substr($nestedId, strlen($prefix)));

            if ($slicing['declaredHere']) {
                $rules[] = [
                    'path'       => $relative,
                    'constraint' => FHIRSlicingRules::class,
                    'options'    => ['property' => $relative, 'rules' => (string) ($slicing['definition']['rules'] ?? 'open')],
                ];
            }

            foreach ($definitions as $nested) {
                $rules[] = [
                    'path'       => $relative,
                    'constraint' => FHIRSliceConstraint::class,
                    'options'    => ['property' => $relative, ...$nested],
                ];
            }
        }

        return $rules;
    }

    /**
     * The value an item must hold at the discriminator path to belong to a slice, or null when it
     * cannot be found without full profile resolution.
     *
     * The snapshot is searched first, in its order, so an inherited slice keeps the value its parent
     * declared ahead of one the profile adds beneath it: AU Core's SystolicBP stays discriminated by
     * LOINC 8480-6, not by the SNOMED coding it adds. A discriminator path looks through nested
     * slices ("code.coding.code" matches "code.coding:SBPCode.code"), but a value outside any nested
     * slice is preferred. For extension slicing on `url`, the value is the extension's profile URL.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     */
    private static function discriminatorValue(
        string $sliceId,
        string $discType,
        string $discPath,
        array $diff,
        array $snapshot,
    ): mixed {
        if (!in_array($discType, ['value', 'pattern'], true) || $discPath === '' || $discPath === '$this') {
            return null;
        }

        $prefix = $sliceId . '.';

        foreach ([$snapshot, $diff] as $source) {
            $nestedValue = null;

            foreach ($source as $id => $element) {
                if (!str_starts_with($id, $prefix)) {
                    continue;
                }

                $relative = self::rewriteChoiceTypeSlices(substr($id, strlen($prefix)));
                if (preg_replace('/:[^.]+/', '', $relative) !== $discPath) {
                    continue;
                }

                $field = ElementDefinitionReader::extractPolymorphicField($element, 'fixed')
                    ?? ElementDefinitionReader::extractPolymorphicField($element, 'pattern');
                if ($field === null) {
                    continue;
                }

                if (!str_contains($relative, ':')) {
                    return $field['value'];
                }

                $nestedValue ??= $field['value'];
            }

            if ($nestedValue !== null) {
                return $nestedValue;
            }
        }

        if ($discPath === 'url') {
            $header = $snapshot[$sliceId] ?? $diff[$sliceId] ?? [];

            return $header['type'][0]['profile'][0] ?? null;
        }

        return null;
    }

    /**
     * Ids of the elements directly beneath a scope that are sliced: those a differential id slices
     * ("Observation.code.coding" for "Observation.code.coding:snomedBPCode.system"), and those that
     * declare `slicing`. The scope is '' for the whole profile, or a slice id; an element beneath a
     * deeper slice belongs to that slice's scope.
     *
     * @param array<string, array<string, mixed>> $diff Differential elements by id
     *
     * @return list<string>
     */
    private static function slicedElementIds(array $diff, string $scope): array
    {
        $prefix = $scope === '' ? '' : $scope . '.';
        $ids    = [];

        foreach ($diff as $id => $element) {
            if (!str_starts_with($id, $prefix)) {
                continue;
            }

            $relative = substr($id, strlen($prefix));
            $slice    = self::firstSliceIn($relative);

            if ($slice !== null) {
                $ids[$prefix . $slice[0]] = true;
            } elseif (isset($element['slicing'])) {
                $ids[$id] = true;
            }
        }

        return array_keys($ids);
    }

    /**
     * Ids of every element the differential slices, at any depth, whether or not its slicing can be
     * found: "Observation.component:SystolicBP.code.coding:snomedSBP.code" gives
     * "Observation.component" and "Observation.component:SystolicBP.code.coding".
     *
     * @param array<string, array<string, mixed>> $diff Differential elements by id
     *
     * @return list<string>
     */
    private static function allSlicedElementIds(array $diff): array
    {
        $ids = [];

        foreach (array_keys($diff) as $id) {
            $scope    = '';
            $relative = $id;

            while (($slice = self::firstSliceIn($relative)) !== null) {
                $slicedId       = $scope === '' ? $slice[0] : "{$scope}.{$slice[0]}";
                $ids[$slicedId] = true;
                $scope          = "{$slicedId}:{$slice[1]}";

                if (!str_starts_with($id, $scope . '.')) {
                    break;
                }

                $relative = substr($id, strlen($scope) + 1);
            }
        }

        return array_keys($ids);
    }

    /**
     * The first slice in a relative id, as [path of the sliced element, slice name], skipping
     * choice type slices: "code.coding:snomedSBP.code" → ['code.coding', 'snomedSBP'].
     *
     * @return array{0: string, 1: string}|null
     */
    private static function firstSliceIn(string $relative): ?array
    {
        $segments = explode('.', $relative);

        foreach ($segments as $index => $segment) {
            $colon = strpos($segment, ':');
            if ($colon === false) {
                continue;
            }

            $element   = substr($segment, 0, $colon);
            $sliceName = substr($segment, $colon + 1);
            if (self::isChoiceTypeSlice($element, $sliceName)) {
                continue;
            }

            return [implode('.', [...array_slice($segments, 0, $index), $element]), $sliceName];
        }

        return null;
    }

    /**
     * Names of the slices on a sliced element that a set of elements declares or reaches into, in
     * their order. Reslices ("a/b") and choice type slices are left out.
     *
     * @param array<string, array<string, mixed>> $diff Differential (or snapshot) elements by id
     *
     * @return list<string>
     */
    private static function sliceNames(string $slicedId, array $diff): array
    {
        $prefix  = $slicedId . ':';
        $segment = substr($slicedId, (int) strrpos($slicedId, '.') + 1);
        $names   = [];

        foreach (array_keys($diff) as $id) {
            if (!str_starts_with($id, $prefix)) {
                continue;
            }

            $rest = substr($id, strlen($prefix));
            $dot  = strpos($rest, '.');
            $name = $dot === false ? $rest : substr($rest, 0, $dot);

            if ($name !== '' && !str_contains($name, '/') && !self::isChoiceTypeSlice($segment, $name)) {
                $names[$name] = true;
            }
        }

        return array_keys($names);
    }

    /**
     * The slicing definition of a sliced element, from this differential when it declares it, else
     * from the snapshot, which carries the slicing a profile inherits.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     *
     * @return array{definition: array<string, mixed>, declaredHere: bool}|null
     */
    private static function slicingFor(string $slicedId, array $diff, array $snapshot): ?array
    {
        $declared = $diff[$slicedId]['slicing'] ?? null;
        if (is_array($declared)) {
            return ['definition' => $declared, 'declaredHere' => true];
        }

        $inherited = $snapshot[$slicedId]['slicing'] ?? null;

        return is_array($inherited) ? ['definition' => $inherited, 'declaredHere' => false] : null;
    }

    /**
     * Whether a slice on an element is a choice type slice ("value[x]" sliced as "valueQuantity").
     */
    private static function isChoiceTypeSlice(string $element, string $sliceName): bool
    {
        if (!str_ends_with($element, '[x]')) {
            return false;
        }

        return preg_match('/^' . preg_quote(substr($element, 0, -3), '/') . '[A-Z]/', $sliceName) === 1;
    }

    /**
     * Differential elements keyed by id, with an id synthesised for any element that has none.
     *
     * Ids are what tie a slice's children to the slice. Without them, a child belongs to the slice
     * header that most recently opened at its path or an ancestor's, since a differential lists each
     * slice's children right after the slice; a later element at a sliced path closes the scopes
     * nested beneath it.
     *
     * @param array<string, mixed> $structureDefinition
     *
     * @return array<string, array<string, mixed>>
     */
    private static function differentialElements(array $structureDefinition): array
    {
        $elements = $structureDefinition['differential']['element'] ?? [];
        $byId     = [];
        /** @var array<string, string> $open path → slice name whose children follow */
        $open = [];

        foreach (is_array($elements) ? $elements : [] as $element) {
            if (!is_array($element)) {
                continue;
            }

            $path      = (string) ($element['path'] ?? '');
            $sliceName = (string) ($element['sliceName'] ?? '');

            foreach (array_keys($open) as $openPath) {
                if (str_starts_with($openPath, $path . '.')) {
                    unset($open[$openPath]);
                }
            }

            if ($sliceName !== '') {
                $open[$path] = $sliceName;
            } else {
                unset($open[$path]);
            }

            $id = (string) ($element['id'] ?? '');
            if ($id === '') {
                $id = self::synthesiseId($path, $sliceName, $open);
            }

            $element['id'] = $id;
            $byId[$id]     = $element;
        }

        return $byId;
    }

    /**
     * Builds an element id from its path and the slices open at its ancestors:
     * "Composition.section.code" under open slice "overview" → "Composition.section:overview.code".
     *
     * @param array<string, string> $open Path → slice name open at that path
     */
    private static function synthesiseId(string $path, string $sliceName, array $open): string
    {
        $segments = explode('.', $path);
        $last     = count($segments) - 1;
        $prefix   = '';
        $id       = '';

        foreach ($segments as $index => $segment) {
            $prefix = $index === 0 ? $segment : "{$prefix}.{$segment}";
            $id     = $index === 0 ? $segment : "{$id}.{$segment}";

            if ($index === $last) {
                $id .= $sliceName !== '' ? ':' . $sliceName : '';
            } elseif (isset($open[$prefix])) {
                $id .= ':' . $open[$prefix];
            }
        }

        return $id;
    }

    /**
     * Snapshot elements keyed by id; empty when the StructureDefinition carries no snapshot.
     *
     * @param array<string, mixed> $structureDefinition
     *
     * @return array<string, array<string, mixed>>
     */
    private static function snapshotElements(array $structureDefinition): array
    {
        $elements = $structureDefinition['snapshot']['element'] ?? [];
        $byId     = [];

        foreach (is_array($elements) ? $elements : [] as $element) {
            if (is_array($element) && isset($element['id'])) {
                $byId[(string) $element['id']] = $element;
            }
        }

        return $byId;
    }

    /**
     * The property path a plain profile rule on this differential element applies to, without the
     * resource/type prefix ("Patient.name" → "name"), or null when the element is a slice or lies
     * beneath one.
     *
     * A slice element keeps the unsliced `path` ("Composition.section.code") and names its slice in
     * `id` ("Composition.section:overview.code"), so a rule emitted on its `path` would bind every
     * item of the sliced element. The slicing header itself ("Composition.section" with `slicing`) is
     * not a slice.
     *
     * A type slice on a choice element ("Observation.value[x]:valueQuantity") is the exception: it
     * selects one variant rather than a subset of items, so it maps to that variant's JSON key
     * ("valueQuantity"), which the validator resolves to the choice property when it holds that type.
     *
     * @param array<string, mixed> $element A differential element with an id (see differentialElements())
     */
    private static function differentialPropertyPath(array $element): ?string
    {
        $path = self::rewriteChoiceTypeSlices((string) ($element['id'] ?? ''));
        if (str_contains($path, ':')) {
            return null;
        }

        $dotPos = strpos($path, '.');

        return $dotPos !== false ? substr($path, $dotPos + 1) : $path;
    }

    /**
     * Rewrites each choice type slice in an element id or path to its variant's JSON key:
     * "Observation.value[x]:valueQuantity.system" → "Observation.valueQuantity.system". A slice not
     * named for a variant of its choice element keeps its ':'.
     */
    private static function rewriteChoiceTypeSlices(string $id): string
    {
        return (string) preg_replace('/(^|\.)(\w+)\[x\]:(\2[A-Z]\w*)(?=\.|$)/', '$1$3', $id);
    }
}
