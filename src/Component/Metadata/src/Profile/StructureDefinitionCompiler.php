<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAllowedTypes;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAnyOfProfiles;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMaxLength;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
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
 * Data-type profiles this profile names on its elements are the exception: they have no group of
 * their own on the resource, so their rules are compiled into this profile's group (see
 * inlinedDataTypeProfiles()), unless the parent already names the same profile on that element.
 *
 * Output order is part of the contract: generated classes must stay byte-identical, so attributes
 * come out as differential constraints, then must-support, then slices, then snapshot obligations,
 * then the rules of data-type profiles the differential names on its elements.
 *
 * @author Ardenexal
 */
final class StructureDefinitionCompiler
{
    /** Canonical URL prefix of the core definitions, base types included. */
    private const string CORE_BASE = 'http://hl7.org/fhir/StructureDefinition/';

    /** How deep data-type profiles may nest inside each other before inlining stops. */
    public const int MAX_INLINE_DEPTH = 8;

    /** Elements typed `Resource` in R4, R4B or R5, other than `<Type>.contained`: slots a profile can narrow to concrete resource types. */
    private const array RESOURCE_SLOTS = [
        'Bundle.entry.resource',
        'Bundle.entry.response.outcome',
        'Bundle.issues',
        'Parameters.parameter.resource',
    ];

    /** Type codes that allow any resource. */
    private const array ABSTRACT_RESOURCE_TYPES = ['Resource', 'DomainResource', 'CanonicalResource', 'MetadataResource'];

    /**
     * Compile one StructureDefinition into its attributes and any diagnostics.
     *
     * With $lookup, a data-type profile the differential names in an element's `type.profile` is
     * compiled too and its rules are added under that element's path, in this profile's group (see
     * inlinedDataTypeProfiles()). Without it, those references are left alone, as before.
     *
     * @param array<string, mixed>                                 $structureDefinition StructureDefinition with derivation=constraint
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds a StructureDefinition by canonical URL, without a version
     */
    public function compile(array $structureDefinition, ?\Closure $lookup = null): CompiledProfile
    {
        $profileUrl  = (string) ($structureDefinition['url'] ?? '');
        $diagnostics = [];
        $visited     = [self::canonicalWithoutVersion($profileUrl) => true];
        $inline      = $lookup === null ? null : self::sliceInliner($structureDefinition, $profileUrl, $lookup, $visited, 1, '', $diagnostics);

        $attributes = [
            ...self::differentialConstraints($structureDefinition, $profileUrl, $lookup),
            ...self::differentialMustSupport($structureDefinition, $profileUrl),
            ...self::differentialSliceConstraints($structureDefinition, $profileUrl, $diagnostics, $inline, $lookup),
            ...self::snapshotObligations($structureDefinition, $profileUrl),
            ...self::differentialInvariants($structureDefinition, [$profileUrl], $lookup, $diagnostics, slicesCarryRules: true),
            ...self::differentialBindings($structureDefinition, [$profileUrl], $lookup, $diagnostics, slicesCarryRules: true),
        ];

        if ($lookup !== null) {
            foreach (self::inlinedDataTypeProfiles($structureDefinition, $profileUrl, $lookup, $visited, 1, $diagnostics) as $attribute) {
                $attributes[] = $attribute;
            }
        }

        return new CompiledProfile($profileUrl, $attributes, $diagnostics);
    }

    /**
     * Compile an extension's StructureDefinition: where it may appear, and what value or sub-extensions it holds.
     *
     * Contexts and context invariants become the attributes a generated extension class carries,
     * in the order the generator writes them. The value types and sub-extension slices are read
     * from the snapshot the way the generator reads them to build the class's constructor.
     *
     * @param array<string, mixed> $structureDefinition An extension definition: type Extension, derivation constraint
     */
    public function compileExtension(array $structureDefinition): CompiledExtension
    {
        $contexts = [];
        foreach (is_array($structureDefinition['context'] ?? null) ? $structureDefinition['context'] : [] as $context) {
            if (!is_array($context) || !isset($context['type'], $context['expression'])) {
                continue;
            }
            // A context limited to some FHIR versions carries its range; the validator ignores the context in other versions
            $contexts[] = new CompiledAttribute(FHIRExtensionContext::class, [
                'type'       => (string) $context['type'],
                'expression' => (string) $context['expression'],
                ...self::versionSpecificUse($context),
            ]);
        }

        foreach (is_array($structureDefinition['contextInvariant'] ?? null) ? $structureDefinition['contextInvariant'] : [] as $invariant) {
            if (!is_string($invariant) || $invariant === '') {
                continue;
            }
            $contexts[] = new CompiledAttribute(FHIRContextInvariant::class, ['expression' => $invariant]);
        }

        $elements = is_array($structureDefinition['snapshot']['element'] ?? null) ? $structureDefinition['snapshot']['element'] : [];
        $byId     = [];
        foreach ($elements as $element) {
            if (is_array($element) && is_string($element['id'] ?? null)) {
                $byId[$element['id']] = $element;
            }
        }

        // Read the way FHIRExtensionGenerator reads them: the first value[x] that may occur, with a
        // type entry without a code taken as string, and every Extension.extension with a slice name
        $valueTypes    = null;
        $subExtensions = [];
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }
            $path = $element['path'] ?? null;

            if ($valueTypes === null && $path === 'Extension.value[x]' && (string) ($element['max'] ?? '1') !== '0') {
                $valueTypes = [];
                foreach (is_array($element['type'] ?? null) ? $element['type'] : [] as $type) {
                    $valueTypes[] = is_array($type) && is_string($type['code'] ?? null) ? $type['code'] : 'string';
                }
            }

            if ($path === 'Extension.extension' && isset($element['sliceName']) && is_scalar($element['sliceName'])) {
                $sliceName       = (string) $element['sliceName'];
                $sliceId         = is_string($element['id'] ?? null) ? $element['id'] : "Extension.extension:{$sliceName}";
                $subExtensions[] = new ExtensionSlice(
                    $sliceName,
                    (int) ($element['min'] ?? 0),
                    isset($element['max']) && is_scalar($element['max']) ? (string) $element['max'] : null,
                    self::typeCodes($byId[$sliceId . '.value[x]'] ?? [])[0] ?? null,
                );
            }
        }

        // Invariants and bindings apply wherever the extension is used, so they carry no group. Those on
        // a sub-extension sit inside a slice and come back as diagnostics
        $diagnostics = [];
        $constraints = [
            ...self::differentialInvariants($structureDefinition, null, null, $diagnostics),
            ...self::differentialBindings($structureDefinition, null, null, $diagnostics),
        ];

        $range = self::versionSpecificUse($structureDefinition);

        return new CompiledExtension(
            (string) ($structureDefinition['url'] ?? ''),
            $contexts,
            $valueTypes ?? [],
            $subExtensions,
            $constraints,
            $diagnostics,
            self::isModifierExtension($structureDefinition),
            $range['startFhirVersion'] ?? null,
            $range['endFhirVersion']   ?? null,
        );
    }

    /**
     * Whether an extension definition's root `Extension` element is a modifier.
     *
     * Read from the snapshot, which states it on every definition, else from the differential.
     *
     * @param array<string, mixed> $structureDefinition
     */
    private static function isModifierExtension(array $structureDefinition): bool
    {
        foreach (['snapshot', 'differential'] as $view) {
            foreach (is_array($structureDefinition[$view]['element'] ?? null) ? $structureDefinition[$view]['element'] : [] as $element) {
                if (is_array($element) && ($element['path'] ?? null) === 'Extension') {
                    return ($element['isModifier'] ?? false) === true;
                }
            }
        }

        return false;
    }

    /**
     * The FHIR version range a `version-specific-use` extension sets on a definition or one of its contexts.
     *
     * Only the bounds that are stated are returned, keyed as the extension names them, so a context
     * with no range compiles to its type and expression alone.
     *
     * @param array<mixed> $owner the StructureDefinition, or one `context` entry
     *
     * @return array{startFhirVersion?: string, endFhirVersion?: string}
     */
    private static function versionSpecificUse(array $owner): array
    {
        $range = [];
        foreach (is_array($owner['extension'] ?? null) ? $owner['extension'] : [] as $extension) {
            if (!is_array($extension) || ($extension['url'] ?? null) !== 'http://hl7.org/fhir/StructureDefinition/version-specific-use') {
                continue;
            }
            foreach (is_array($extension['extension'] ?? null) ? $extension['extension'] : [] as $bound) {
                $name = is_array($bound) ? ($bound['url'] ?? null) : null;
                if (($name === 'startFhirVersion' || $name === 'endFhirVersion') && is_string($bound['valueCode'] ?? null) && $bound['valueCode'] !== '') {
                    $range[$name] = $bound['valueCode'];
                }
            }
        }

        return $range;
    }

    /**
     * The type codes an element allows, in order.
     *
     * @param array<string, mixed> $element
     *
     * @return list<string>
     */
    private static function typeCodes(array $element): array
    {
        $codes = [];
        foreach (is_array($element['type'] ?? null) ? $element['type'] : [] as $type) {
            if (is_array($type) && is_string($type['code'] ?? null) && $type['code'] !== '') {
                $codes[] = $type['code'];
            }
        }

        return $codes;
    }

    /**
     * The rules of each data-type profile this differential names in an element's `type.profile`,
     * moved under that element's path and into the group of the profile being compiled.
     *
     * A profile that types `Observation.referenceRange.high` as SimpleQuantity requires every
     * `referenceRange.high` to be a SimpleQuantity, so SimpleQuantity's `comparator` max 0 becomes
     * `referenceRange.high.comparator` max 0 in the referencing profile. The data-type profile's own
     * constraint ancestors come along, and so do the data-type profiles it names in turn, until
     * MAX_INLINE_DEPTH. Its cardinality, fixed and pattern rules and its slices are inlined; its
     * must-support and obligations are not, since they describe the data type, not the referencing
     * profile, and no validator reads them.
     *
     * An element on or beneath a slice is left to sliceRules(), which inlines its profile into that
     * slice's rules through sliceInliner(), so it holds for the slice's items only.
     *
     * Only an element that allows one type with one profile is inlined. Several types or several
     * profiles mean the value must match any one of them, which these rules cannot express, so such an
     * element, a profile no lookup finds, one that is not a constraint on a data type, a cycle, nesting
     * past the limit and a data-type profile ancestor no lookup finds are reported as diagnostics and
     * not enforced. An element that also allows its base data type is skipped without one: any value
     * of that type conforms. Extension profiles are left to extension validation.
     *
     * A `|version` on the referenced URL is dropped: the lookup decides which version it returns.
     *
     * @param array<string, mixed>                          $structureDefinition Definition whose differential is read
     * @param string                                        $groupUrl            URL of the profile being compiled; every rule goes in its group
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     * @param array<string, true>                           $visited             Canonical URLs on the current inlining path
     * @param list<CompilerDiagnostic>                      $diagnostics
     *
     * @return list<CompiledAttribute> Rules with paths relative to $structureDefinition's root type
     */
    private static function inlinedDataTypeProfiles(
        array $structureDefinition,
        string $groupUrl,
        \Closure $lookup,
        array $visited,
        int $depth,
        array &$diagnostics,
        string $trail = '',
    ): array {
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $id => $element) {
            $propertyPath = self::differentialPropertyPath($element);

            // An element inside a slice holds for that slice's items only: sliceRules() inlines it
            // into the slice's rules through sliceInliner()
            if ($propertyPath === null) {
                continue;
            }

            $at = $trail === '' ? $propertyPath : "{$trail}.{$propertyPath}";
            foreach (self::inlinedProfileAt($structureDefinition, $id, $element, $propertyPath, $at, $trail, $groupUrl, $lookup, $visited, $depth, $diagnostics) as $attribute) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }

    /**
     * The rules of the data-type profile one differential element names, moved under $path; see
     * inlinedDataTypeProfiles() for what is inlined and what is reported instead.
     *
     * @param array<string, mixed>                          $structureDefinition Definition the element belongs to
     * @param array<string, mixed>                          $element             The differential element
     * @param string                                        $path                Where the rules go: a property path, or an item-relative one inside a slice ('' for the slice's items)
     * @param string                                        $at                  Where the profile is inlined, as later diagnostics name it
     * @param string                                        $trail               Where $structureDefinition itself is inlined; '' for the profile being compiled
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     * @param array<string, true>                           $visited             Canonical URLs on the current inlining path
     * @param list<CompilerDiagnostic>                      $diagnostics
     *
     * @return list<CompiledAttribute>
     */
    private static function inlinedProfileAt(
        array $structureDefinition,
        string $id,
        array $element,
        string $path,
        string $at,
        string $trail,
        string $groupUrl,
        \Closure $lookup,
        array $visited,
        int $depth,
        array &$diagnostics,
    ): array {
        if (!str_contains((string) ($element['path'] ?? ''), '.') || ElementDefinitionReader::hasContentReference($element)) {
            return [];
        }


        $types    = is_array($element['type'] ?? null) ? array_values($element['type']) : [];
        $profiled = [];
        foreach ($types as $type) {
            $profiles = is_array($type['profile'] ?? null) ? array_values(array_filter($type['profile'], 'is_string')) : [];
            if ($profiles !== [] && ($type['code'] ?? null) !== 'Extension') {
                $profiled[] = $profiles;
            }
        }
        if ($profiled === []) {
            return [];
        }

        // Where the element sits: its own id, and inside a data-type profile also where that
        // profile is inlined, so a nested diagnostic can be traced back
        $sourceUrl   = (string) ($structureDefinition['url'] ?? '');
        $where       = $trail === '' ? $id : "{$id} in {$sourceUrl}, inlined at {$trail}";
        $named       = implode(', ', array_merge(...$profiled));
        $notEnforced = static function(string $reason) use (&$diagnostics, $groupUrl, $id, $where, $named): void {
            $diagnostics[] = new CompilerDiagnostic(
                "Profile {$groupUrl}: {$where} must conform to {$named}, but {$reason}; those rules are not enforced",
                $id,
                ['profileUrl' => $groupUrl, 'typeProfile' => $named],
            );
        };

        // A value may match any one of several types or profiles: elementRules() checks it against
        // each on the side (FHIRAnyOfProfiles), since their rules cannot be merged into one list
        if (count($types) > 1 || count($profiled[0]) > 1) {
            return [];
        }

        $url = self::canonicalWithoutVersion($profiled[0][0]);
        if (isset($visited[$url])) {
            $notEnforced("{$url} is already being inlined on this path, so the profiles refer to each other");

            return [];
        }
        if ($depth > self::MAX_INLINE_DEPTH) {
            $notEnforced('data-type profiles nest more than ' . self::MAX_INLINE_DEPTH . ' deep');

            return [];
        }

        // A profile that restates the type its parent already gave the element would report each
        // violation twice, once in each group; the parent's group already enforces it
        if ($depth === 1 && self::parentTypesElementTheSame($structureDefinition, $id, $profiled[0], $lookup)) {
            return [];
        }

        $definition = $lookup($url);
        if ($definition === null) {
            $notEnforced('no StructureDefinition with that URL is available');

            return [];
        }
        if (($definition['kind'] ?? null) !== 'complex-type' || ($definition['derivation'] ?? null) !== 'constraint') {
            $notEnforced('it is not a constraint on a data type');

            return [];
        }

        // The data-type profile and each constraint ancestor, up to the base data type. Like a
        // profile requested directly, each one must carry a snapshot: slicing a differential
        // inherits is only found there.
        $current = $definition;
        $seen    = $visited;
        $chain   = [];
        while (is_array($current) && ($current['derivation'] ?? null) === 'constraint') {
            $currentUrl        = self::canonicalWithoutVersion((string) ($current['url'] ?? ''));
            $seen[$currentUrl] = true;

            if (self::snapshotElements($current) === []) {
                $notEnforced("{$currentUrl} has no snapshot, which runtime validation requires");
                $chain = [];
                break;
            }

            $chain[] = [
                ...self::differentialConstraints($current, $groupUrl),
                ...self::differentialSliceConstraints($current, $groupUrl, $diagnostics, self::sliceInliner($current, $groupUrl, $lookup, $seen, $depth + 1, $at, $diagnostics), $lookup),
                ...self::differentialInvariants($current, [$groupUrl], $lookup, $diagnostics, slicesCarryRules: true),
                ...self::differentialBindings($current, [$groupUrl], $lookup, $diagnostics, slicesCarryRules: true),
                ...self::inlinedDataTypeProfiles($current, $groupUrl, $lookup, $seen, $depth + 1, $diagnostics, $at),
            ];

            $parent = is_string($current['baseDefinition'] ?? null) ? self::canonicalWithoutVersion($current['baseDefinition']) : '';
            if ($parent === '' || isset($seen[$parent])) {
                break;
            }
            $current = $lookup($parent);
            if ($current === null && $parent !== self::CORE_BASE . ($definition['type'] ?? '')) {
                $notEnforced("{$currentUrl} derives from {$parent}, which is not available, so its rules are missing");
            }
        }

        $attributes = [];
        foreach (self::mergeInheritedSlices(array_merge(...($chain === [] ? [[]] : $chain))) as $rule) {
            $attributes[] = self::underPath($rule, $path);
        }

        return $attributes;
    }

    /**
     * Whether every type an element restricts by profile also allows its base data type, as
     * `Identifier` named beside `au-ihi`, or an unprofiled `Identifier` entry beside it: any value of
     * that type then conforms, so the profiles add no rule.
     *
     * @param list<mixed> $types The element's `type` entries
     */
    private static function allowsBaseType(array $types): bool
    {
        $restricted = [];
        $open       = [];
        foreach ($types as $type) {
            if (!is_array($type) || !is_string($type['code'] ?? null)) {
                continue;
            }

            $profiles = array_map(self::canonicalWithoutVersion(...), is_array($type['profile'] ?? null) ? array_filter($type['profile'], 'is_string') : []);
            if ($profiles === [] || in_array(self::CORE_BASE . $type['code'], $profiles, true)) {
                $open[$type['code']] = true;
            } else {
                $restricted[$type['code']] = true;
            }
        }

        return array_diff_key($restricted, $open) === [];
    }

    /**
     * A closure that inlines the data-type profile an element on or beneath a slice names, as that
     * slice's rules at the element's item-relative path. sliceRules() calls it for each such element.
     *
     * @param array<string, mixed>                          $structureDefinition Definition whose slices are compiled
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     * @param array<string, true>                           $visited             Canonical URLs on the current inlining path
     * @param list<CompilerDiagnostic>                      $diagnostics         Receives what cannot be inlined
     *
     * @return \Closure(string, array<string, mixed>, string): list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function sliceInliner(
        array $structureDefinition,
        string $groupUrl,
        \Closure $lookup,
        array $visited,
        int $depth,
        string $trail,
        array &$diagnostics,
    ): \Closure {
        return static function(string $id, array $element, string $relative) use ($structureDefinition, $groupUrl, $lookup, $visited, $depth, $trail, &$diagnostics): array {
            $at = $trail === '' ? $id : "{$trail}.{$id}";

            return self::asSliceRules(self::inlinedProfileAt($structureDefinition, $id, $element, $relative, $at, $trail, $groupUrl, $lookup, $visited, $depth, $diagnostics));
        };
    }

    /**
     * Compiled attributes as slice rules: the same constraints, without their groups, since a slice
     * rule runs in the group of the slice that carries it.
     *
     * @param list<CompiledAttribute> $attributes
     *
     * @return list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function asSliceRules(array $attributes): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            $arguments = $attribute->arguments;
            unset($arguments['groups']);

            if ($attribute->class === FHIRProfileConstraint::class) {
                /** @var class-string $constraint */
                $constraint = $arguments['constraint'];
                $rules[]    = ['path' => (string) $arguments['path'], 'constraint' => $constraint, 'options' => is_array($arguments['options'] ?? null) ? $arguments['options'] : []];

                continue;
            }

            $path    = (string) ($arguments['path'] ?? $arguments['property'] ?? '');
            $rules[] = ['path' => $path, 'constraint' => $attribute->class, 'options' => $arguments];
        }

        return $rules;
    }

    /**
     * One slice definition per slice, where a data-type profile and its ancestors each compiled one.
     *
     * A resource profile and its parent check their slices in separate groups. An inlined data-type
     * profile and its ancestors all land in the referencing profile's group, and the slice validator
     * gives each item to the first slice it matches, so two definitions of one slice would leave the
     * second with no items. They are merged instead, most-derived first: the tighter cardinality of
     * the two, the first discriminator value found, and the rules of both. Slicing rules declared at
     * several levels keep the most-derived one.
     *
     * @param list<CompiledAttribute> $attributes Most-derived definition's rules first
     *
     * @return list<CompiledAttribute>
     */
    private static function mergeInheritedSlices(array $attributes): array
    {
        $merged  = [];
        $slices  = [];
        $slicing = [];

        foreach ($attributes as $attribute) {
            $arguments = $attribute->arguments;
            $property  = (string) ($arguments['property'] ?? '');

            if ($attribute->class === FHIRSlicingRules::class) {
                if (isset($slicing[$property])) {
                    continue;
                }
                $slicing[$property] = true;
            }

            if ($attribute->class === FHIRSliceConstraint::class) {
                $key = $property . ':' . (string) ($arguments['sliceName'] ?? '');
                if (isset($slices[$key])) {
                    $index          = $slices[$key];
                    $merged[$index] = new CompiledAttribute(FHIRSliceConstraint::class, self::mergeSlice($merged[$index]->arguments, $arguments));
                    continue;
                }
                $slices[$key] = count($merged);
            }

            $merged[] = $attribute;
        }

        return array_values($merged);
    }

    /**
     * @param array<string, mixed> $derived  Arguments of the more derived definition
     * @param array<string, mixed> $ancestor Arguments of the ancestor's definition of the same slice
     *
     * @return array<string, mixed>
     */
    private static function mergeSlice(array $derived, array $ancestor): array
    {
        $derived['min'] = max((int) ($derived['min'] ?? 0), (int) ($ancestor['min'] ?? 0));

        $maxes = array_filter([$derived['max'] ?? '*', $ancestor['max'] ?? '*'], static fn (mixed $max): bool => $max !== '*');
        if ($maxes !== []) {
            $derived['max'] = min(array_map('intval', $maxes));
        }

        // A discriminator value the ancestor found fills one the derived definition lacks
        $discriminators = is_array($derived['discriminators'] ?? null) ? $derived['discriminators'] : [];
        $inherited      = is_array($ancestor['discriminators'] ?? null) ? $ancestor['discriminators'] : [];
        foreach ($discriminators as $index => $discriminator) {
            $from = $inherited[$index] ?? [];
            if (self::unmatchableDiscriminators([$discriminator]) !== [] && ($from['path'] ?? null) === ($discriminator['path'] ?? null)) {
                $discriminators[$index] = $from;
            }
        }
        $derived['discriminators'] = $discriminators;

        $rules = is_array($derived['rules'] ?? null) ? $derived['rules'] : [];
        foreach (is_array($ancestor['rules'] ?? null) ? $ancestor['rules'] : [] as $rule) {
            if (!in_array($rule, $rules, true)) {
                $rules[] = $rule;
            }
        }
        if ($rules !== []) {
            $derived['rules'] = $rules;
        }

        return $derived;
    }

    /**
     * Whether the parent profile's snapshot already types this element with the same profiles.
     *
     * @param array<string, mixed>                          $structureDefinition
     * @param list<string>                                  $profiles
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     */
    private static function parentTypesElementTheSame(array $structureDefinition, string $id, array $profiles, \Closure $lookup): bool
    {
        // Only a parent that allows exactly this one profiled type inlined it. One that allowed several
        // checks at most that the value matches one of them, so the narrowed child must inline its
        // own profile itself
        $element = self::parentSnapshotElement($structureDefinition, $id, $lookup);
        $types   = is_array($element['type'] ?? null) ? array_values($element['type']) : [];
        if (count($types) !== 1 || !is_array($types[0]['profile'] ?? null)) {
            return false;
        }
        $inherited = array_values(array_map(self::canonicalWithoutVersion(...), array_filter($types[0]['profile'], 'is_string')));

        return $inherited === array_map(self::canonicalWithoutVersion(...), $profiles);
    }

    /**
     * Whether the parent profile's snapshot types this element with the same types and profiles, in
     * any order, so the parent's group already checks the same alternatives.
     *
     * @param array<string, mixed>                          $structureDefinition
     * @param array<string, mixed>                          $element             The differential element
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     */
    private static function parentTypesElementAlike(array $structureDefinition, string $id, array $element, \Closure $lookup): bool
    {
        $inherited = self::parentSnapshotElement($structureDefinition, $id, $lookup);

        return $inherited !== null && self::typeSignature($inherited) === self::typeSignature($element);
    }

    /**
     * The element with this id in the parent profile's snapshot, or null when the parent is a base
     * resource or data type (which types no element with a profile) or cannot be found.
     *
     * @param array<string, mixed>                          $structureDefinition
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     *
     * @return array<string, mixed>|null
     */
    private static function parentSnapshotElement(array $structureDefinition, string $id, \Closure $lookup): ?array
    {
        $base = is_string($structureDefinition['baseDefinition'] ?? null) ? self::canonicalWithoutVersion($structureDefinition['baseDefinition']) : '';
        if ($base === '') {
            return null;
        }

        $parent = $lookup($base);
        if ($parent === null || ($parent['derivation'] ?? null) !== 'constraint') {
            return null;
        }

        return self::snapshotElements($parent)[$id] ?? null;
    }

    /**
     * An element's types as sorted `code|profile` strings, profiles without their version, so two
     * elements typed alike compare equal whatever order they list them in.
     *
     * @param array<string, mixed> $element
     *
     * @return list<string>
     */
    private static function typeSignature(array $element): array
    {
        $signature = [];
        foreach (is_array($element['type'] ?? null) ? $element['type'] : [] as $type) {
            if (!is_array($type) || !is_string($type['code'] ?? null)) {
                continue;
            }

            $profiles = is_array($type['profile'] ?? null) ? array_filter($type['profile'], 'is_string') : [];
            if ($profiles === []) {
                $signature[] = $type['code'];
            }
            foreach ($profiles as $profile) {
                $signature[] = $type['code'] . '|' . self::canonicalWithoutVersion($profile);
            }
        }
        sort($signature);

        return $signature;
    }

    /**
     * The same rule, applied to the element at $prefix instead of the data type's root.
     */
    private static function underPath(CompiledAttribute $attribute, string $prefix): CompiledAttribute
    {
        $arguments = $attribute->arguments;
        $key       = array_key_exists('path', $arguments) ? 'path' : 'property';
        if ($prefix !== '' && is_string($arguments[$key] ?? null)) {
            // An invariant on the data type itself has no path of its own
            $arguments[$key] = $arguments[$key] === '' ? $prefix : $prefix . '.' . $arguments[$key];
        }

        return new CompiledAttribute($attribute->class, $arguments);
    }

    /**
     * "http://x/StructureDefinition/a|1.0" → "http://x/StructureDefinition/a".
     */
    private static function canonicalWithoutVersion(string $url): string
    {
        $pipe = strpos($url, '|');

        return $pipe === false ? $url : substr($url, 0, $pipe);
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
     * A FHIRAnyOfProfiles the parent profile already places on the same element, with the same types
     * and profiles, is left to the parent's group, so a failing value is reported once.
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds the parent profile
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialConstraints(array $structureDefinition, string $profileUrl, ?\Closure $lookup = null): array
    {
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $id => $element) {
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
                if ($rule['constraint'] === FHIRAnyOfProfiles::class && $lookup !== null && self::parentTypesElementAlike($structureDefinition, (string) $id, $element, $lookup)) {
                    continue;
                }

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
     * One FHIRProfileInvariant per invariant a differential element declares.
     *
     * An invariant on the root element applies to the profiled type itself and gets an empty path.
     * Skipped invariants:
     *  - Those with no FHIRPath expression (XPath only)
     *  - Those another definition declares (`source` names it), as the base model generator skips
     *    them: that definition's own class or group enforces them
     *  - Those the base type already declares on the same element, found through $lookup, which
     *    a differential may restate
     *  - Those on an element inside a slice: with $slicesCarryRules they are compiled into that
     *    slice's `rules` instead (see sliceRules()), otherwise they are reported as diagnostics
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param list<string>|null                                    $groups              Validation groups; null for the default group
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds the base type's definition
     * @param list<CompilerDiagnostic>                             $diagnostics
     * @param bool                                                 $slicesCarryRules    Whether this definition's slices compile to FHIRSliceConstraint rules
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialInvariants(array $structureDefinition, ?array $groups, ?\Closure $lookup, array &$diagnostics, bool $slicesCarryRules = false): array
    {
        $url        = self::canonicalWithoutVersion((string) ($structureDefinition['url'] ?? ''));
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $id => $element) {
            $constraints = is_array($element['constraint'] ?? null) ? $element['constraint'] : [];
            if ($constraints === [] || ElementDefinitionReader::hasContentReference($element)) {
                continue;
            }

            $path         = (string) ($element['path'] ?? '');
            $propertyPath = str_contains($path, '.') ? self::differentialPropertyPath($element) : '';
            if ($propertyPath === null && $slicesCarryRules) {
                continue;
            }
            $inherited = null;

            foreach ($constraints as $constraint) {
                if (!is_array($constraint) || !is_string($constraint['expression'] ?? null) || $constraint['expression'] === '') {
                    continue;
                }

                $key    = (string) ($constraint['key'] ?? '');
                $source = is_string($constraint['source'] ?? null) ? self::canonicalWithoutVersion($constraint['source']) : null;
                if ($source !== null && $source !== $url) {
                    continue;
                }

                $inherited ??= self::baseElementRules($structureDefinition, $path, $lookup)['keys'];
                if (in_array($key, $inherited, true)) {
                    continue;
                }

                if ($propertyPath === null) {
                    $diagnostics[] = new CompilerDiagnostic(
                        "Profile {$url}: invariant {$key} on {$id} is inside a slice; it is not enforced",
                        $id,
                        ['profileUrl' => $url, 'invariant' => $key],
                    );
                    continue;
                }

                $arguments = [
                    'path'       => $propertyPath,
                    'key'        => $key,
                    'severity'   => (string) ($constraint['severity'] ?? 'error'),
                    'expression' => $constraint['expression'],
                    'human'      => (string) ($constraint['human'] ?? ''),
                ];
                if (self::isBestPractice($constraint)) {
                    $arguments['bestPractice'] = true;
                }
                if ($groups !== null) {
                    $arguments['groups'] = $groups;
                }

                $attributes[] = new CompiledAttribute(FHIRProfileInvariant::class, $arguments);
            }
        }

        return $attributes;
    }

    /**
     * One FHIRProfileBinding per required or extensible binding a differential element declares.
     *
     * Skipped bindings:
     *  - `preferred` and `example` ones, which are guidance
     *  - One the base type already declares on the same element with the same strength and value
     *    set, found through $lookup: the base model's own binding checks it
     *  - Those on an element inside a slice: with $slicesCarryRules they are compiled into that
     *    slice's `rules` instead (see sliceRules()), otherwise they are reported as diagnostics
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param list<string>|null                                    $groups              Validation groups; null for the default group
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds the base type's definition
     * @param list<CompilerDiagnostic>                             $diagnostics
     * @param bool                                                 $slicesCarryRules    Whether this definition's slices compile to FHIRSliceConstraint rules
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialBindings(array $structureDefinition, ?array $groups, ?\Closure $lookup, array &$diagnostics, bool $slicesCarryRules = false): array
    {
        $url        = self::canonicalWithoutVersion((string) ($structureDefinition['url'] ?? ''));
        $attributes = [];

        foreach (self::differentialElements($structureDefinition) as $id => $element) {
            $binding  = is_array($element['binding'] ?? null) ? $element['binding'] : [];
            $strength = $binding['strength'] ?? null;
            $valueSet = $binding['valueSet'] ?? null;
            if (!in_array($strength, ['required', 'extensible'], true) || !is_string($valueSet) || $valueSet === '') {
                continue;
            }

            $path = (string) ($element['path'] ?? '');
            if (!str_contains($path, '.') || ElementDefinitionReader::hasContentReference($element)) {
                continue;
            }

            if ($slicesCarryRules && self::differentialPropertyPath($element) === null) {
                continue;
            }

            $base = self::baseElementRules($structureDefinition, $path, $lookup)['binding'];
            if ($base === [$strength, self::canonicalWithoutVersion($valueSet)]) {
                continue;
            }

            $propertyPath = self::differentialPropertyPath($element);
            if ($propertyPath === null) {
                $diagnostics[] = new CompilerDiagnostic(
                    "Profile {$url}: the {$strength} binding to {$valueSet} on {$id} is inside a slice; it is not enforced",
                    $id,
                    ['profileUrl' => $url, 'valueSet' => $valueSet],
                );
                continue;
            }

            $arguments = ['path' => $propertyPath, 'valueSetUrl' => $valueSet, 'strength' => $strength];
            if ($groups !== null) {
                $arguments['groups'] = $groups;
            }

            $attributes[] = new CompiledAttribute(FHIRProfileBinding::class, $arguments);
        }

        return $attributes;
    }

    /**
     * What the base type declares on the element at $path: its invariant keys, and its binding as
     * [strength, value set without version]. Empty when $lookup is null or does not find the type.
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup
     *
     * @return array{keys: list<string>, binding: array{0: mixed, 1: string}|null}
     */
    private static function baseElementRules(array $structureDefinition, string $path, ?\Closure $lookup): array
    {
        $type = $structureDefinition['type'] ?? null;
        $base = $lookup !== null && is_string($type) ? $lookup(self::CORE_BASE . $type) : null;

        $elements = is_array($base) && is_array($base['snapshot']['element'] ?? null) ? $base['snapshot']['element'] : [];
        foreach ($elements as $element) {
            if (!is_array($element) || ($element['path'] ?? null) !== $path || isset($element['sliceName'])) {
                continue;
            }

            $keys = [];
            foreach (is_array($element['constraint'] ?? null) ? $element['constraint'] : [] as $constraint) {
                if (is_array($constraint) && is_string($constraint['key'] ?? null)) {
                    $keys[] = $constraint['key'];
                }
            }

            $binding = is_array($element['binding'] ?? null) ? $element['binding'] : [];
            $bound   = is_string($binding['valueSet'] ?? null) ? [$binding['strength'] ?? null, self::canonicalWithoutVersion($binding['valueSet'])] : null;

            return ['keys' => $keys, 'binding' => $bound];
        }

        return ['keys' => [], 'binding' => null];
    }

    /**
     * Whether a constraint carries `elementdefinition-bestpractice` set to true.
     *
     * @param array<mixed> $constraint
     */
    private static function isBestPractice(array $constraint): bool
    {
        foreach (is_array($constraint['extension'] ?? null) ? $constraint['extension'] : [] as $extension) {
            if (is_array($extension)
                && is_string($extension['url'] ?? null)
                && str_ends_with($extension['url'], '/elementdefinition-bestpractice')
                && ($extension['valueBoolean'] ?? false) === true) {
                return true;
            }
        }

        return false;
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
     * items that match the parent's. Inherited ordered slicing is the exception: it gets open rules
     * with `ordered`, so the slices this profile adds are held to their order among themselves.
     *
     * Slicing beneath a slice ("component:SystolicBP.code.coding:snomedSBP") travels in that slice's
     * `rules` as nested slice definitions, which the validator applies within each matching item.
     * Reslices ("extension:altid/npi") travel there too, at the path '', and the validator matches
     * the slice's items among them; see sliceRules().
     *
     * Limitations:
     *  - Every discriminator of the slicing is compiled into the slice's `discriminators`; see
     *    sliceDiscriminator() for where each value comes from.
     *  - A slice with a discriminator that has nothing to match on (a path through `resolve()`, or a
     *    value, pattern, presence, type or profile the definitions do not state) gets no minimum,
     *    since it could only fail. Closed or openAtEnd slicing with such a slice is enforced as open,
     *    and its default slice is left out; the relaxed rules are reported as a diagnostic.
     *  - Choice type slices ("value[x]:valueQuantity") are left to the plain constraints, which put
     *    them on the variant path.
     *  - A slice on slicing found in neither the differential nor the snapshot (a StructureDefinition
     *    built without a snapshot that slices inherited slicing) cannot be compiled; it is reported as
     *    a diagnostic rather than dropped silently.
     *
     * @param array<string, mixed>                                                                                                                      $structureDefinition
     * @param list<CompilerDiagnostic>                                                                                                                  $diagnostics         Receives one entry per slicing that cannot be found
     * @param (\Closure(string, array<string, mixed>, string): list<array{path: string, constraint: class-string, options: array<string, mixed>}>)|null $inline              Inlines a data-type profile named inside a slice; see sliceInliner()
     * @param (\Closure(string): (array<string, mixed>|null))|null                                                                                      $lookup              Finds the data-type profile a slice is typed with, for its discriminator value
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialSliceConstraints(array $structureDefinition, string $profileUrl, array &$diagnostics, ?\Closure $inline = null, ?\Closure $lookup = null): array
    {
        $diff       = self::differentialElements($structureDefinition);
        $snapshot   = self::snapshotElements($structureDefinition);
        $parent     = self::parentSnapshotElements($structureDefinition, $lookup);
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

            $slices = self::sliceDefinitions($slicedId, $slicing, $diff, $snapshot, $profileUrl, $diagnostics, $inline, $lookup, parentSnapshot: $parent);
            if ($slices === []) {
                continue;
            }

            // "Observation.code.coding" → "code.coding"
            $propertyPath = self::rewriteChoiceTypeSlices(substr($slicedId, $dotPos + 1));

            if ($slicing['declaredHere']) {
                $attributes[] = new CompiledAttribute(FHIRSlicingRules::class, [
                    'property' => $propertyPath,
                    'rules'    => self::enforceableRules($slicedId, $slicing, $slices, $profileUrl, $diagnostics),
                    'groups'   => [$profileUrl],
                    ...self::orderedOption($slicedId, $slicing, $snapshot),
                ]);
            } elseif (self::orderedOption($slicedId, $slicing, $snapshot) !== []) {
                // Inherited ordered slicing: the order still binds the slices this profile adds. Open
                // rules are what no FHIRSlicingRules means, so only the order is added.
                $attributes[] = new CompiledAttribute(FHIRSlicingRules::class, [
                    'property' => $propertyPath,
                    'rules'    => 'open',
                    'groups'   => [$profileUrl],
                    'ordered'  => true,
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
     * min > 0 or max is bounded, a fixed[x], scalar or complex, an array pattern[x] (scalar
     * patterns use value matching), a maxLength, the types a choice element or a resource slot may
     * hold, the target profiles a Reference or canonical may point at, and the profiles a value must
     * match one of.
     *
     * @param array<string, mixed> $element
     *
     * @return list<array{constraint: class-string<Count|FHIRFixedValue|FHIRPatternValue|FHIRMaxLength|FHIRAllowedTypes|FHIRTargetProfile|FHIRAnyOfProfiles>, options: array<string, mixed>}>
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
        if ($fixedField !== null && (is_scalar($fixedField['value']) || is_array($fixedField['value']))) {
            $rules[] = ['constraint' => FHIRFixedValue::class, 'options' => ['value' => $fixedField['value']]];
        }

        $patternField = ElementDefinitionReader::extractPolymorphicField($element, 'pattern');
        if ($patternField !== null && is_array($patternField['value'])) {
            $rules[] = ['constraint' => FHIRPatternValue::class, 'options' => ['pattern' => $patternField['value']]];
        }

        // FHIR allows maxLength only on primitive elements; the validator skips a value with no string form
        $maxLength = $element['maxLength'] ?? null;
        if (is_int($maxLength) && $maxLength > 0) {
            $rules[] = ['constraint' => FHIRMaxLength::class, 'options' => ['max' => $maxLength]];
        }

        // The model holds every variant of a choice element in one property, so a profile that lists
        // fewer types than the base needs a rule to say which ones may occur. A choice type slice
        // ("value[x]:valueQuantity") already names its one type in its path, and so has no '[x]'.
        // A resource slot such as Bundle.entry.resource is typed `Resource` in the base, so a profile
        // naming concrete types there (`only Patient`) narrows it the same way
        $types = self::typeCodes($element);
        if ($types !== [] && (str_ends_with(self::rewriteChoiceTypeSlices((string) ($element['id'] ?? $element['path'] ?? '')), '[x]') || self::narrowsResourceSlot($element, $types))) {
            $rules[] = ['constraint' => FHIRAllowedTypes::class, 'options' => ['types' => $types]];
        }

        foreach (ElementDefinitionReader::targetProfileRules($element) as $options) {
            $rules[] = ['constraint' => FHIRTargetProfile::class, 'options' => $options];
        }

        $anyOf = self::anyOfProfiles($element);
        if ($anyOf !== null) {
            $rules[] = ['constraint' => FHIRAnyOfProfiles::class, 'options' => $anyOf];
        }

        return $rules;
    }

    /**
     * The profiles a value must match one of, when the element's types name several profiles or
     * several profiled types, and the type codes it allows without one; null when there is no such
     * choice.
     *
     * One type with one profile is left to inlinedDataTypeProfiles(), which inlines a data-type
     * profile's rules and reports any other profile as not enforced.
     * An element that also allows the base type of every profiled type gets no rule, since any value
     * of that type conforms (AU Core's `Identifier` beside `au-ihi`). Extension profiles are left to
     * extension validation, so an Extension type counts as allowed without a profile.
     *
     * @param array<string, mixed> $element
     *
     * @return array{profiles: list<string>, openTypes: list<string>}|null
     */
    private static function anyOfProfiles(array $element): ?array
    {
        $types = is_array($element['type'] ?? null) ? array_values($element['type']) : [];
        if (count($types) < 2 && count(is_array($types[0]['profile'] ?? null) ? $types[0]['profile'] : []) < 2) {
            return null;
        }
        if (self::allowsBaseType($types)) {
            return null;
        }

        $profiles  = [];
        $openTypes = [];
        foreach ($types as $type) {
            if (!is_array($type) || !is_string($type['code'] ?? null)) {
                continue;
            }

            $named = is_array($type['profile'] ?? null) ? array_values(array_filter($type['profile'], 'is_string')) : [];
            if ($named === [] || $type['code'] === 'Extension') {
                $openTypes[] = $type['code'];

                continue;
            }

            foreach ($named as $profile) {
                $profiles[] = $profile;
            }
        }

        return $profiles === [] ? null : ['profiles' => array_values(array_unique($profiles)), 'openTypes' => array_values(array_unique($openTypes))];
    }

    /**
     * Whether an element narrows a base element typed `Resource` to concrete resource types.
     *
     * The slot is read from the element's `path`, which names it whatever slice the element sits on.
     * A published differential carries no `base`, so `path` is what identifies the slot; `contained`
     * is inherited from DomainResource and so appears under every resource type. A type list that
     * still names an abstract type allows any resource, so it narrows nothing.
     *
     * @param array<string, mixed> $element
     * @param list<string>         $types   The element's type codes
     */
    private static function narrowsResourceSlot(array $element, array $types): bool
    {
        $path = is_string($element['path'] ?? null) ? $element['path'] : '';
        $slot = in_array($path, self::RESOURCE_SLOTS, true) || preg_match('~\A[A-Z][A-Za-z]+\.contained\z~', $path) === 1;

        return $slot && array_intersect($types, self::ABSTRACT_RESOURCE_TYPES) === [];
    }

    /**
     * `ordered: true` for a FHIRSlicingRules whose slicing is ordered, else nothing, so unordered
     * slicing compiles exactly as before.
     *
     * A differential that restates the slicing without `ordered` keeps the snapshot's: a derived
     * profile cannot make ordered slicing unordered.
     *
     * @param string                                                      $slicedId Id of the sliced element
     * @param array{definition: array<string, mixed>, declaredHere: bool} $slicing  From slicingFor()
     * @param array<string, array<string, mixed>>                         $snapshot Snapshot elements by id
     *
     * @return array{ordered?: true}
     */
    private static function orderedOption(string $slicedId, array $slicing, array $snapshot): array
    {
        $inherited = is_array($snapshot[$slicedId]['slicing'] ?? null) ? $snapshot[$slicedId]['slicing'] : [];
        $ordered   = $slicing['definition']['ordered'] ?? $inherited['ordered'] ?? false;

        return $ordered === true ? ['ordered' => true] : [];
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
     * @param string                                                                                                                                    $slicedId       Id of the sliced element, e.g. 'Observation.component'
     * @param array{definition: array<string, mixed>, declaredHere: bool}                                                                               $slicing        Its slicing, from slicingFor()
     * @param array<string, array<string, mixed>>                                                                                                       $diff           Differential elements by id
     * @param array<string, array<string, mixed>>                                                                                                       $snapshot       Snapshot elements by id
     * @param string                                                                                                                                    $profileUrl     URL of the profile being compiled
     * @param list<CompilerDiagnostic>                                                                                                                  $diagnostics    Receives slicing beneath a slice that is enforced as open
     * @param (\Closure(string, array<string, mixed>, string): list<array{path: string, constraint: class-string, options: array<string, mixed>}>)|null $inline         Inlines a data-type profile named inside a slice; see sliceInliner()
     * @param (\Closure(string): (array<string, mixed>|null))|null                                                                                      $lookup         Finds the data-type profile a slice is typed with, for its discriminator value
     * @param string                                                                                                                                    $resliceOf      Name of the slice whose reslices to define, or '' for the element's own slices
     * @param array<string, array<string, mixed>>|null                                                                                                  $parentSnapshot The parent profile's snapshot elements by id, when found; marks the parent's slices `inherited`
     *
     * @return list<array<string, mixed>>
     */
    private static function sliceDefinitions(
        string $slicedId,
        array $slicing,
        array $diff,
        array $snapshot,
        string $profileUrl,
        array &$diagnostics,
        ?\Closure $inline = null,
        ?\Closure $lookup = null,
        string $resliceOf = '',
        ?array $parentSnapshot = null,
    ): array {
        $definition     = $slicing['definition'];
        $discriminators = [];
        foreach (is_array($definition['discriminator'] ?? null) ? $definition['discriminator'] : [] as $discriminator) {
            if (is_array($discriminator)) {
                $discriminators[] = ['type' => (string) ($discriminator['type'] ?? 'value'), 'path' => (string) ($discriminator['path'] ?? '')];
            }
        }

        // Closed or openAtEnd rules declared here, and ordered slicing, judge every item by the slice
        // it matches, so the snapshot's slices join this profile's as bare members
        $slicingId = $resliceOf === '' ? $slicedId : "{$slicedId}:{$resliceOf}";
        $names     = self::sliceNames($slicedId, $diff, $resliceOf);
        $inherited = [];
        if ($names !== [] && (($slicing['declaredHere'] && ($definition['rules'] ?? 'open') !== 'open') || self::orderedOption($slicingId, $slicing, $snapshot) !== [])) {
            $names = array_values(array_unique([...$names, ...self::sliceNames($slicedId, $snapshot, $resliceOf)]));

            // The parent's slices, restated here or not, are marked inherited: the parent's group
            // already reports the order among them. Without the parent, those this differential
            // leaves alone are taken to be the parent's.
            $inherited = array_flip($parentSnapshot !== null
                ? array_values(array_intersect($names, self::sliceNames($slicedId, $parentSnapshot, $resliceOf)))
                : array_values(array_diff($names, self::sliceNames($slicedId, $diff, $resliceOf))));
        }

        // Declaration order is the snapshot's, which lists every slice of the slicing; the merged
        // list above puts the differential's slices first. A slice the snapshot lacks (a
        // StructureDefinition without one) keeps its differential position, after the snapshot's.
        $declared = array_flip(self::sliceNames($slicedId, $snapshot, $resliceOf));

        $definitions = [];
        foreach ($names as $position => $sliceName) {
            $orderedIndex = $declared[$sliceName] ?? count($declared) + $position;
            $sliceId      = "{$slicedId}:{$sliceName}";
            $header       = $diff[$sliceId] ?? [];
            $isDefault    = $sliceName === '@default';
            $max          = (string) ($header['max'] ?? '*');

            $sliceDiscriminators = [];
            foreach ($discriminators as $discriminator) {
                $sliceDiscriminators[] = $isDefault
                    ? $discriminator
                    : self::resolvedDiscriminator($slicedId, $sliceName, $discriminator, $diff, $snapshot, $lookup);
            }

            // An item can only join a slice its discriminator can match, so a minimum on any other
            // slice could only fail. The default slice takes the items no named slice matched.
            $matchable = $isDefault || self::unmatchableDiscriminators($sliceDiscriminators) === [];

            $definition = [
                'sliceName'      => $sliceName,
                'min'            => $matchable ? (int) ($header['min'] ?? 0) : 0,
                'max'            => is_numeric($max) ? (int) $max : $max,
                'discriminators' => $sliceDiscriminators,
            ];

            if ($isDefault) {
                $definition['isDefault'] = true;
            }

            $definition['orderedIndex'] = $orderedIndex;

            if (isset($inherited[$sliceName])) {
                $definition['inherited'] = true;
            }

            $rules = self::sliceRules($sliceId, $diff, $snapshot, $profileUrl, $diagnostics, $inline, $lookup, $parentSnapshot);
            if ($rules !== []) {
                $definition['rules'] = $rules;

                // No item ever joins a slice its discriminator cannot match, so its rules never run
                if (self::isUnmatchable($definition)) {
                    $diagnostics[] = new CompilerDiagnostic(
                        "Profile {$profileUrl}: {$sliceId} carries rules, but " . self::describeDiscriminators(self::unmatchableDiscriminators($sliceDiscriminators)) . ' cannot be evaluated, so no item is matched to it; those rules are not enforced',
                        $sliceId,
                        ['profileUrl' => $profileUrl],
                    );
                }
            }

            $definitions[] = $definition;
        }

        // The default slice takes the items no named slice matched. While a named slice cannot match
        // its own items, they would land there too and be held to the default slice's rules.
        if (array_filter($definitions, self::isUnmatchable(...)) !== []) {
            $definitions = array_values(array_filter($definitions, static fn (array $d): bool => !($d['isDefault'] ?? false)));
        }

        return $definitions;
    }

    /**
     * True for a named slice with a discriminator that has nothing to match on, so no item ever
     * matches it: a path through `resolve()`, or a value, pattern, presence, type, profile or
     * required binding the compiler did not find.
     *
     * @param array<string, mixed> $definition A slice definition from sliceDefinitions()
     */
    private static function isUnmatchable(array $definition): bool
    {
        if ($definition['isDefault'] ?? false) {
            return false;
        }

        $discriminators = array_values(array_filter(is_array($definition['discriminators'] ?? null) ? $definition['discriminators'] : [], 'is_array'));

        return $discriminators === [] || self::unmatchableDiscriminators($discriminators) !== [];
    }

    /**
     * The discriminators of a slice that carry neither a value nor a value set to match on, nor
     * their reslices' alternatives (`anyOf`).
     *
     * @param list<array<mixed>> $discriminators
     *
     * @return list<array<mixed>>
     */
    private static function unmatchableDiscriminators(array $discriminators): array
    {
        return array_values(array_filter(
            $discriminators,
            static fn (array $d): bool => !array_key_exists('value', $d) && !array_key_exists('valueSet', $d) && !array_key_exists('anyOf', $d),
        ));
    }

    /**
     * "its pattern discriminator on 'type'", or "its value discriminators on 'system', 'code'".
     *
     * @param list<array<mixed>> $discriminators
     */
    private static function describeDiscriminators(array $discriminators): string
    {
        $types = array_values(array_unique(array_map(static fn (array $d): string => (string) ($d['type'] ?? ''), $discriminators)));
        $paths = implode(', ', array_map(static fn (array $d): string => "'" . (string) ($d['path'] ?? '') . "'", $discriminators));

        return count($discriminators) === 1
            ? "its {$types[0]} discriminator on {$paths}"
            : 'its ' . implode('/', $types) . " discriminators on {$paths}";
    }

    /**
     * The slicing rules to enforce for a slicing declared here: its own, unless a slice cannot be
     * matched, in which case 'open'.
     *
     * Closed and openAtEnd rules judge every item by the slice it matches. An item belonging to a
     * slice the validator cannot match matches none, so closed rules would reject it and openAtEnd
     * would reject the matched items after it, though the resource conforms. Enforcing 'open' gives
     * up those checks for the whole slicing, and a diagnostic says so.
     *
     * @param array{definition: array<string, mixed>, declaredHere: bool} $slicing     Its slicing, from slicingFor()
     * @param list<array<string, mixed>>                                  $slices      Its slices, from sliceDefinitions()
     * @param list<CompilerDiagnostic>                                    $diagnostics Receives one entry when the rules are relaxed
     */
    private static function enforceableRules(string $slicedId, array $slicing, array $slices, string $profileUrl, array &$diagnostics): string
    {
        $rules = (string) ($slicing['definition']['rules'] ?? 'open');
        if ($rules === 'open') {
            return $rules;
        }

        $unmatchable = array_column(array_filter($slices, self::isUnmatchable(...)), 'sliceName');
        if ($unmatchable === []) {
            return $rules;
        }

        $diagnostics[] = new CompilerDiagnostic(
            sprintf(
                'Profile %s slices %s with %s rules, but slice(s) %s use a discriminator that cannot be evaluated; the slicing is enforced as open',
                $profileUrl,
                $slicedId,
                $rules,
                implode(', ', $unmatchable),
            ),
            $slicedId,
            ['profileUrl' => $profileUrl],
        );

        return 'open';
    }

    /**
     * The rules this differential places beneath one slice, with paths relative to a slice item.
     *
     * Invariants and required or extensible bindings on the slice itself or beneath it become
     * FHIRProfileInvariant and FHIRProfileBinding rules, whose own `path` is item-relative ('' for
     * the item); see sliceInvariantsAndBindings() for the ones left out.
     *
     * A sliced element beneath the slice contributes one nested FHIRSliceConstraint definition per
     * slice, plus its FHIRSlicingRules when the slicing is declared here; the validator applies them
     * to that element's items within each item matching this slice.
     *
     * @param string                                                                                                                                    $sliceId        Id of the slice, e.g. 'Observation.component:SystolicBP'
     * @param array<string, array<string, mixed>>                                                                                                       $diff           Differential elements by id
     * @param array<string, array<string, mixed>>                                                                                                       $snapshot       Snapshot elements by id
     * @param string                                                                                                                                    $profileUrl     URL of the profile being compiled
     * @param list<CompilerDiagnostic>                                                                                                                  $diagnostics    Receives slicing beneath the slice that is enforced as open
     * @param (\Closure(string, array<string, mixed>, string): list<array{path: string, constraint: class-string, options: array<string, mixed>}>)|null $inline         Inlines a data-type profile named on the slice or beneath it; see sliceInliner()
     * @param (\Closure(string): (array<string, mixed>|null))|null                                                                                      $lookup         Finds the data-type profile a nested slice is typed with, for its discriminator value
     * @param array<string, array<string, mixed>>|null                                                                                                  $parentSnapshot The parent profile's snapshot elements by id, when found; see sliceDefinitions()
     *
     * @return list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function sliceRules(string $sliceId, array $diff, array $snapshot, string $profileUrl, array &$diagnostics, ?\Closure $inline = null, ?\Closure $lookup = null, ?array $parentSnapshot = null): array
    {
        $prefix = $sliceId . '.';
        $rules  = [];

        if (isset($diff[$sliceId])) {
            $rules = self::sliceInvariantsAndBindings($sliceId, '', $diff[$sliceId], $snapshot, $profileUrl);
            foreach ($inline === null ? [] : $inline($sliceId, $diff[$sliceId], '') as $rule) {
                $rules[] = $rule;
            }
        }

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

            foreach (self::sliceInvariantsAndBindings($id, $relative, $element, $snapshot, $profileUrl) as $rule) {
                $rules[] = $rule;
            }

            foreach ($inline === null ? [] : $inline($id, $element, $relative) as $rule) {
                $rules[] = $rule;
            }
        }

        foreach (self::slicedElementIds($diff, $sliceId) as $nestedId) {
            $slicing = self::slicingFor($nestedId, $diff, $snapshot);
            if ($slicing === null) {
                continue;
            }

            // As at the top level, a slicing with no slices to check compiles to nothing: closed rules
            // with no slice beside them would reject every item
            $definitions = self::sliceDefinitions($nestedId, $slicing, $diff, $snapshot, $profileUrl, $diagnostics, $inline, $lookup, parentSnapshot: $parentSnapshot);
            if ($definitions === []) {
                continue;
            }

            $relative = self::rewriteChoiceTypeSlices(substr($nestedId, strlen($prefix)));

            foreach (self::nestedSlicingRules($relative, $nestedId, $slicing, $definitions, $snapshot, $profileUrl, $diagnostics) as $rule) {
                $rules[] = $rule;
            }
        }

        // Reslices ("altid/npi") split the items matching this slice, so their slicing sits at the
        // path '', which the validator reads as those items rather than an element beneath each one
        $colon     = (int) strrpos($sliceId, ':');
        $slicedId  = substr($sliceId, 0, $colon);
        $sliceName = substr($sliceId, $colon + 1);
        if (self::sliceNames($slicedId, $diff, $sliceName) !== []) {
            $reslicing   = self::reslicingFor($sliceId, $slicedId, $diff, $snapshot);
            $definitions = $reslicing === null ? [] : self::sliceDefinitions($slicedId, $reslicing, $diff, $snapshot, $profileUrl, $diagnostics, $inline, $lookup, $sliceName, $parentSnapshot);

            foreach ($reslicing === null || $definitions === [] ? [] : self::nestedSlicingRules('', $sliceId, $reslicing, $definitions, $snapshot, $profileUrl, $diagnostics) as $rule) {
                $rules[] = $rule;
            }
        }

        return $rules;
    }

    /**
     * Slicing beneath a slice as slice rules at $relative: its FHIRSlicingRules when the slicing is
     * declared here or is inherited ordered slicing, as at the top level, then one FHIRSliceConstraint
     * per slice.
     *
     * @param string                                                      $relative    Item-relative path of the sliced element; '' for a slice's reslices
     * @param string                                                      $slicingId   Id of the element carrying the slicing
     * @param array{definition: array<string, mixed>, declaredHere: bool} $slicing     From slicingFor() or reslicingFor()
     * @param list<array<string, mixed>>                                  $definitions From sliceDefinitions()
     * @param array<string, array<string, mixed>>                         $snapshot    Snapshot elements by id
     * @param list<CompilerDiagnostic>                                    $diagnostics Receives slicing enforced as open
     *
     * @return list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function nestedSlicingRules(string $relative, string $slicingId, array $slicing, array $definitions, array $snapshot, string $profileUrl, array &$diagnostics): array
    {
        $rules = [];

        if ($slicing['declaredHere']) {
            $rules[] = [
                'path'       => $relative,
                'constraint' => FHIRSlicingRules::class,
                'options'    => [
                    'property' => $relative,
                    'rules'    => self::enforceableRules($slicingId, $slicing, $definitions, $profileUrl, $diagnostics),
                    ...self::orderedOption($slicingId, $slicing, $snapshot),
                ],
            ];
        } elseif (self::orderedOption($slicingId, $slicing, $snapshot) !== []) {
            // Inherited ordered slicing, as at the top level
            $rules[] = [
                'path'       => $relative,
                'constraint' => FHIRSlicingRules::class,
                'options'    => ['property' => $relative, 'rules' => 'open', 'ordered' => true],
            ];
        }

        foreach ($definitions as $nested) {
            $rules[] = [
                'path'       => $relative,
                'constraint' => FHIRSliceConstraint::class,
                'options'    => ['property' => $relative, ...$nested],
            ];
        }

        return $rules;
    }

    /**
     * The invariants and required or extensible bindings one differential element on or beneath a
     * slice adds, as slice rules at $relative, an item-relative path ('' for the slice itself).
     *
     * Left out, as differentialInvariants() and differentialBindings() leave them out at the top
     * level:
     *  - Invariants another definition declares (`source` names it): that definition enforces them
     *  - Invariants and bindings the same element outside any slice already carries in the snapshot,
     *    which every item is held to anyway: the base type's, or one the profile places on all items
     *  - `preferred` and `example` bindings, which are guidance
     *
     * @param string                              $id         The element's id, e.g. 'Patient.identifier:ihi.value'
     * @param string                              $relative   Its path within a slice item, e.g. 'value'
     * @param array<string, mixed>                $element    The differential element
     * @param array<string, array<string, mixed>> $snapshot   Snapshot elements by id
     * @param string                              $profileUrl URL of the profile being compiled
     *
     * @return list<array{path: string, constraint: class-string, options: array<string, mixed>}>
     */
    private static function sliceInvariantsAndBindings(string $id, string $relative, array $element, array $snapshot, string $profileUrl): array
    {
        $url       = self::canonicalWithoutVersion($profileUrl);
        $unsliced  = $snapshot[(string) preg_replace('/:[^.]+/', '', self::rewriteChoiceTypeSlices($id))] ?? [];
        $inherited = [];
        foreach (is_array($unsliced['constraint'] ?? null) ? $unsliced['constraint'] : [] as $constraint) {
            if (is_array($constraint) && is_string($constraint['key'] ?? null)) {
                $inherited[] = $constraint['key'];
            }
        }

        $rules = [];
        foreach (is_array($element['constraint'] ?? null) ? $element['constraint'] : [] as $constraint) {
            if (!is_array($constraint) || !is_string($constraint['expression'] ?? null) || $constraint['expression'] === '') {
                continue;
            }

            $key    = (string) ($constraint['key'] ?? '');
            $source = is_string($constraint['source'] ?? null) ? self::canonicalWithoutVersion($constraint['source']) : null;
            if (($source !== null && $source !== $url) || in_array($key, $inherited, true)) {
                continue;
            }

            $options = [
                'path'       => $relative,
                'key'        => $key,
                'severity'   => (string) ($constraint['severity'] ?? 'error'),
                'expression' => $constraint['expression'],
                'human'      => (string) ($constraint['human'] ?? ''),
            ];
            if (self::isBestPractice($constraint)) {
                $options['bestPractice'] = true;
            }

            $rules[] = ['path' => $relative, 'constraint' => FHIRProfileInvariant::class, 'options' => $options];
        }

        $binding  = is_array($element['binding'] ?? null) ? $element['binding'] : [];
        $strength = $binding['strength'] ?? null;
        $valueSet = $binding['valueSet'] ?? null;
        if (in_array($strength, ['required', 'extensible'], true) && is_string($valueSet) && $valueSet !== '') {
            $unslicedBinding = is_array($unsliced['binding'] ?? null) ? $unsliced['binding'] : [];
            $sameAsUnsliced  = ($unslicedBinding['strength'] ?? null) === $strength
                && is_string($unslicedBinding['valueSet'] ?? null)
                && self::canonicalWithoutVersion($unslicedBinding['valueSet']) === self::canonicalWithoutVersion($valueSet);

            if (!$sameAsUnsliced) {
                $rules[] = [
                    'path'       => $relative,
                    'constraint' => FHIRProfileBinding::class,
                    'options'    => ['path' => $relative, 'valueSetUrl' => $valueSet, 'strength' => $strength],
                ];
            }
        }

        return $rules;
    }

    /**
     * One discriminator of a slice, as sliceDiscriminator() finds it, or else as the slice's reslices
     * find it.
     *
     * A resliced slice often states no value of its own at a discriminator path, and leaves each of
     * its reslices to fix one ("altid" slices extensions by `value.system`, and "altid/npi" and
     * "altid/ssn" each fix a system). Its items are then those of its reslices, so the discriminator
     * carries each reslice's own discriminator under `anyOf`, and an item matching any one of them
     * passes. A reslice with nothing of its own takes the value of the slice it belongs to (see
     * ancestorSliceDiscriminator()); when there is none, the discriminator is left as found.
     *
     * @param string                                               $slicedId      Id of the sliced element
     * @param string                                               $sliceName     Name of the slice, "a" or a reslice "a/b"
     * @param array{type: string, path: string}                    $discriminator The slicing's discriminator
     * @param array<string, array<string, mixed>>                  $diff          Differential elements by id
     * @param array<string, array<string, mixed>>                  $snapshot      Snapshot elements by id
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup        Finds the data-type profile a slice is typed with
     *
     * @return array{type: string, path: string, value?: mixed, valueSet?: string, anyOf?: list<array{type: string, path: string, value?: mixed, valueSet?: string}>}
     */
    private static function resolvedDiscriminator(string $slicedId, string $sliceName, array $discriminator, array $diff, array $snapshot, ?\Closure $lookup): array
    {
        $found = self::sliceDiscriminator("{$slicedId}:{$sliceName}", $discriminator['type'], $discriminator['path'], $diff, $snapshot, $lookup);
        if (self::unmatchableDiscriminators([$found]) === []) {
            return $found;
        }

        $reslices = array_values(array_unique([...self::sliceNames($slicedId, $snapshot, $sliceName), ...self::sliceNames($slicedId, $diff, $sliceName)]));
        if ($reslices === []) {
            return self::ancestorSliceDiscriminator($slicedId, $sliceName, $discriminator, $diff, $snapshot, $lookup) ?? $found;
        }

        $alternatives = [];
        foreach ($reslices as $reslice) {
            $resolved = self::resolvedDiscriminator($slicedId, $reslice, $discriminator, $diff, $snapshot, $lookup);
            if (self::unmatchableDiscriminators([$resolved]) !== []) {
                return self::ancestorSliceDiscriminator($slicedId, $sliceName, $discriminator, $diff, $snapshot, $lookup) ?? $found;
            }

            foreach ($resolved['anyOf'] ?? [$resolved] as $alternative) {
                if (!in_array($alternative, $alternatives, true)) {
                    $alternatives[] = $alternative;
                }
            }
        }

        return [...$found, 'anyOf' => $alternatives];
    }

    /**
     * The discriminator as the nearest slice a reslice belongs to states it ("a" for "a/b"), or null.
     *
     * A reslice holds only items of its slice, so a value the slice fixes holds for it too. A
     * snapshot repeats that value on the reslice; a differential, or a StructureDefinition without a
     * snapshot, states it only on the slice.
     *
     * @param array{type: string, path: string}                    $discriminator
     * @param array<string, array<string, mixed>>                  $diff
     * @param array<string, array<string, mixed>>                  $snapshot
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup
     *
     * @return array{type: string, path: string, value?: mixed, valueSet?: string}|null
     */
    private static function ancestorSliceDiscriminator(string $slicedId, string $sliceName, array $discriminator, array $diff, array $snapshot, ?\Closure $lookup): ?array
    {
        while (($slash = strrpos($sliceName, '/')) !== false) {
            $sliceName = substr($sliceName, 0, $slash);
            $found     = self::sliceDiscriminator("{$slicedId}:{$sliceName}", $discriminator['type'], $discriminator['path'], $diff, $snapshot, $lookup);

            if (self::unmatchableDiscriminators([$found]) === []) {
                return $found;
            }
        }

        return null;
    }

    /**
     * One discriminator of a slice, with the value an item must hold to belong to it when one is found.
     *
     * The value comes from discriminatorValue(), or else from the data-type profile the slice is typed
     * with. A `value` or `pattern` discriminator that finds neither, where a required binding holds at
     * its path (see discriminatorValueSet()), carries that value set as `valueSet`: the item belongs to
     * the slice when its code is in the value set, which only a terminology client can answer.
     *
     * @param array<string, array<string, mixed>>                  $diff     Differential elements by id
     * @param array<string, array<string, mixed>>                  $snapshot Snapshot elements by id
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup   Finds the data-type profile a slice is typed with
     *
     * @return array{type: string, path: string, value?: mixed, valueSet?: string}
     */
    private static function sliceDiscriminator(string $sliceId, string $discType, string $discPath, array $diff, array $snapshot, ?\Closure $lookup): array
    {
        $discriminator = ['type' => $discType, 'path' => $discPath];
        $header        = $snapshot[$sliceId] ?? $diff[$sliceId] ?? [];

        $value       = self::discriminatorValue($sliceId, $discType, $discPath, $diff, $snapshot);
        $typeProfile = $value === null && $lookup !== null ? self::typeProfileDiscriminator($header, $discType, $discPath, $lookup) : [];
        if ($value !== null || array_key_exists('value', $typeProfile)) {
            $discriminator['value'] = $value ?? $typeProfile['value'];

            return $discriminator;
        }

        if (!in_array($discType, ['value', 'pattern'], true) || str_contains($discPath, 'resolve()')) {
            return $discriminator;
        }

        // A slice that forbids the element at the path takes the items without it, as the slicing
        // profile's `email` slice in core's slicing example forbids `telecom.use`
        if ($discPath !== '$this') {
            $element = $snapshot[$sliceId . '.' . $discPath] ?? $diff[$sliceId . '.' . $discPath] ?? [];
            if ((string) ($element['max'] ?? '') === '0') {
                return ['type' => 'exists', 'path' => $discPath, 'value' => false];
            }
        }

        $valueSet = self::discriminatorValueSet($sliceId, $discPath, $diff, $snapshot) ?? $typeProfile['valueSet'] ?? null;
        if ($valueSet !== null) {
            $discriminator['valueSet'] = $valueSet;
        }

        return $discriminator;
    }

    /**
     * The value set of the required binding nearest the discriminator path, or null.
     *
     * The element at the path is read first, then each ancestor up to the slice itself, from the
     * snapshot first: a binding on a CodeableConcept slice holds for the codings and codes beneath it,
     * so a slice discriminated on `coding.code` is keyed by a required binding on the slice. A binding
     * every item carries, slice or not, is skipped.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     */
    private static function discriminatorValueSet(string $sliceId, string $discPath, array $diff, array $snapshot): ?string
    {
        $segments = $discPath === '$this' ? [] : explode('.', $discPath);
        $slicedId = substr($sliceId, 0, (int) strrpos($sliceId, ':'));

        for ($depth = count($segments); $depth >= 0; --$depth) {
            $relative = array_slice($segments, 0, $depth);
            $id       = implode('.', [$sliceId, ...$relative]);
            $valueSet = self::requiredValueSet($id, $diff, $snapshot);
            if ($valueSet === null) {
                continue;
            }

            // A binding this profile states on the slice tells its items apart. One the snapshot only
            // inherits does so when the unsliced element is bound otherwise; R5 history-bundle's `patch`
            // slice has only the http-verb binding every `entry.request.method` has
            $unsliced = implode('.', [$slicedId, ...$relative]);
            if (self::requiredValueSet($id, $diff, []) === $valueSet
                || (isset($snapshot[$unsliced]) && self::requiredValueSet($unsliced, [], $snapshot) !== $valueSet)) {
                return $valueSet;
            }
        }

        return null;
    }

    /**
     * The value set of an element's required binding, from the snapshot first, or null.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     */
    private static function requiredValueSet(string $id, array $diff, array $snapshot): ?string
    {
        foreach ([$snapshot, $diff] as $source) {
            $binding = is_array($source[$id]['binding'] ?? null) ? $source[$id]['binding'] : [];
            if (($binding['strength'] ?? null) === 'required' && is_string($binding['valueSet'] ?? null)) {
                return $binding['valueSet'];
            }
        }

        return null;
    }

    /**
     * The value an item must hold at the discriminator path to belong to a slice, or null when the
     * definitions state none.
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
        if ($discPath === '' || str_contains($discPath, 'resolve()')) {
            return null;
        }

        if ($discType === 'type' || $discType === 'profile') {
            return self::discriminatorTypes($sliceId, $discType, $discPath, $diff, $snapshot);
        }

        // The slice's own fixed or pattern value: AU Core's smoking status `category:socialHistory`
        if ($discPath === '$this') {
            if (!in_array($discType, ['value', 'pattern'], true)) {
                return null;
            }

            foreach ([$snapshot, $diff] as $source) {
                $field = ElementDefinitionReader::extractPolymorphicField($source[$sliceId] ?? [], 'fixed')
                    ?? ElementDefinitionReader::extractPolymorphicField($source[$sliceId] ?? [], 'pattern');
                if ($field !== null) {
                    return $field['value'];
                }
            }

            return null;
        }

        if ($discType === 'exists') {
            return self::discriminatorPresence($sliceId, $discPath, $diff, $snapshot);
        }

        if (!in_array($discType, ['value', 'pattern'], true)) {
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
     * The type codes (for a `type` discriminator) or profile URLs (for `profile`) the slice allows at
     * the discriminator path, or null when it names none.
     *
     * The element is the slice itself for `$this`, else the one at the path beneath it, read from the
     * snapshot first. A profile URL keeps any `|version`; the matcher decides how to look it up.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     *
     * @return non-empty-list<string>|null
     */
    private static function discriminatorTypes(string $sliceId, string $discType, string $discPath, array $diff, array $snapshot): ?array
    {
        $id = $discPath === '$this' ? $sliceId : $sliceId . '.' . $discPath;

        foreach ([$snapshot, $diff] as $source) {
            $element = $source[$id] ?? $source[$id . '[x]'] ?? null;
            if ($element === null) {
                continue;
            }

            if ($discType === 'type') {
                $codes = self::typeCodes($element);
            } else {
                $codes = [];
                foreach (is_array($element['type'] ?? null) ? $element['type'] : [] as $type) {
                    foreach (is_array($type['profile'] ?? null) ? $type['profile'] : [] as $profile) {
                        if (is_string($profile) && $profile !== '') {
                            $codes[] = $profile;
                        }
                    }
                }
            }

            if ($codes !== []) {
                return array_values(array_unique($codes));
            }
        }

        return null;
    }

    /**
     * What the data-type profile a slice is typed with says about the discriminator path, when the
     * slicing profile states nothing itself: `['value' => ...]` for a fixed or pattern value,
     * `['valueSet' => ...]` for a required binding the profile states, or `[]`.
     *
     * AU Core types `Patient.identifier:ihi` with `au-ihi` and discriminates on `type`, but only
     * `au-ihi` fixes that `type`; `au-dvanumber` only binds it. The profile's snapshot is read at the
     * discriminator path, outside any slice. Only a slice allowing one type with one profile is read,
     * as inlining requires.
     *
     * @param array<string, mixed>                          $header The slice element
     * @param \Closure(string): (array<string, mixed>|null) $lookup
     *
     * @return array{value?: mixed, valueSet?: string}
     */
    private static function typeProfileDiscriminator(array $header, string $discType, string $discPath, \Closure $lookup): array
    {
        $types = is_array($header['type'] ?? null) ? array_values($header['type']) : [];
        if (!in_array($discType, ['value', 'pattern'], true) || $discPath === '' || count($types) !== 1) {
            return [];
        }

        $profiles = is_array($types[0]['profile'] ?? null) ? array_values(array_filter($types[0]['profile'], 'is_string')) : [];
        if (count($profiles) !== 1 || ($types[0]['code'] ?? null) === 'Extension') {
            return [];
        }

        $definition = $lookup(self::canonicalWithoutVersion($profiles[0]));
        if ($definition === null || ($definition['kind'] ?? null) !== 'complex-type') {
            return [];
        }

        $root    = (string) ($definition['type'] ?? '');
        $element = self::snapshotElements($definition)[$discPath === '$this' ? $root : $root . '.' . $discPath] ?? null;
        if ($element === null) {
            return [];
        }

        $field = ElementDefinitionReader::extractPolymorphicField($element, 'fixed')
            ?? ElementDefinitionReader::extractPolymorphicField($element, 'pattern');
        if ($field !== null) {
            return ['value' => $field['value']];
        }

        // Only a binding the data-type profile states itself; one it inherits from the data type
        // holds for every value of that type, in the slice or not
        $id       = $discPath === '$this' ? $root : $root . '.' . $discPath;
        $valueSet = self::requiredValueSet($id, self::differentialElements($definition), []);

        return $valueSet === null ? [] : ['valueSet' => $valueSet];
    }

    /**
     * Whether an item must have (true) or lack (false) the element at an `exists` discriminator's
     * path to belong to a slice, or null when the slice does not say.
     *
     * The slice states it in its own cardinality for that element: `min` 1 or more requires it,
     * `max` "0" forbids it. The snapshot is read first, then the differential.
     *
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     */
    private static function discriminatorPresence(string $sliceId, string $discPath, array $diff, array $snapshot): ?bool
    {
        $id = $sliceId . '.' . $discPath;

        foreach ([$snapshot, $diff] as $source) {
            $element = $source[$id] ?? null;
            if ($element === null) {
                continue;
            }

            if ((string) ($element['max'] ?? '') === '0') {
                return false;
            }

            if ((int) ($element['min'] ?? 0) >= 1) {
                return true;
            }
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
     * their order. Choice type slices are left out.
     *
     * With $resliceOf '' these are the slices of the element itself, and an element of a reslice
     * ("a/b") counts as reaching into its slice ("a"). With a slice name, they are that slice's
     * reslices one level down: "a/b" for "a", with "a/b/c" counting as reaching into "a/b".
     *
     * @param array<string, array<string, mixed>> $diff      Differential (or snapshot) elements by id
     * @param string                              $resliceOf Name of the slice whose reslices to list, or '' for the element's own slices
     *
     * @return list<string>
     */
    private static function sliceNames(string $slicedId, array $diff, string $resliceOf = ''): array
    {
        $prefix  = $slicedId . ':' . ($resliceOf === '' ? '' : $resliceOf . '/');
        $segment = substr($slicedId, (int) strrpos($slicedId, '.') + 1);
        $names   = [];

        foreach (array_keys($diff) as $id) {
            if (!str_starts_with($id, $prefix)) {
                continue;
            }

            $rest  = substr($id, strlen($prefix));
            $dot   = strpos($rest, '.');
            $name  = $dot === false ? $rest : substr($rest, 0, $dot);
            $slash = strpos($name, '/');
            $name  = $slash === false ? $name : substr($name, 0, $slash);

            if ($name === '' || ($resliceOf === '' && self::isChoiceTypeSlice($segment, $name))) {
                continue;
            }

            $names[$resliceOf === '' ? $name : "{$resliceOf}/{$name}"] = true;
        }

        return array_keys($names);
    }

    /**
     * The slicing that tells a slice's reslices apart: the one declared on the slice itself (in this
     * differential, else in the snapshot), else the sliced element's discriminators with open rules.
     *
     * Profiles often reslice without restating slicing on the slice: the sliced element's
     * discriminators already name the paths, and each reslice fixes the values there. The HL7
     * validator matches such reslices that way too. The rules are open, since no closed or ordered
     * reslicing was declared, and the result counts as declared here only when this differential
     * declares slicing on the slice.
     *
     * @param string                              $sliceId  Id of the resliced slice, e.g. 'AuditEvent.agent.extension:altid'
     * @param string                              $slicedId Id of the element it slices, e.g. 'AuditEvent.agent.extension'
     * @param array<string, array<string, mixed>> $diff     Differential elements by id
     * @param array<string, array<string, mixed>> $snapshot Snapshot elements by id
     *
     * @return array{definition: array<string, mixed>, declaredHere: bool}|null
     */
    private static function reslicingFor(string $sliceId, string $slicedId, array $diff, array $snapshot): ?array
    {
        $own = self::slicingFor($sliceId, $diff, $snapshot);
        if ($own !== null) {
            return $own;
        }

        $outer = self::slicingFor($slicedId, $diff, $snapshot);
        if ($outer === null) {
            return null;
        }

        return ['definition' => ['discriminator' => $outer['definition']['discriminator'] ?? [], 'rules' => 'open'], 'declaredHere' => false];
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
     * The snapshot elements of the profile this one constrains, keyed by id, or null when there is
     * no lookup or it does not find the parent.
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup
     *
     * @return array<string, array<string, mixed>>|null
     */
    private static function parentSnapshotElements(array $structureDefinition, ?\Closure $lookup): ?array
    {
        $base   = is_string($structureDefinition['baseDefinition'] ?? null) ? self::canonicalWithoutVersion($structureDefinition['baseDefinition']) : '';
        $parent = $base === '' || $lookup === null ? null : $lookup($base);

        return $parent === null ? null : self::snapshotElements($parent);
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
