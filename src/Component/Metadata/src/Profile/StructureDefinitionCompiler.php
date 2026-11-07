<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
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

        $attributes = [
            ...self::differentialConstraints($structureDefinition, $profileUrl),
            ...self::differentialMustSupport($structureDefinition, $profileUrl),
            ...self::differentialSliceConstraints($structureDefinition, $profileUrl, $diagnostics),
            ...self::snapshotObligations($structureDefinition, $profileUrl),
            ...self::differentialInvariants($structureDefinition, [$profileUrl], $lookup, $diagnostics),
            ...self::differentialBindings($structureDefinition, [$profileUrl], $lookup, $diagnostics),
        ];

        if ($lookup !== null) {
            $visited = [self::canonicalWithoutVersion($profileUrl) => true];
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
            $contexts[] = new CompiledAttribute(FHIRExtensionContext::class, [
                'type'       => (string) $context['type'],
                'expression' => (string) $context['expression'],
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

        return new CompiledExtension((string) ($structureDefinition['url'] ?? ''), $contexts, $valueTypes ?? [], $subExtensions, $constraints, $diagnostics);
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
     * Only an element that allows one type with one profile is inlined. Several types or several
     * profiles mean the value must match any one of them, which these rules cannot express, so such an
     * element, a profile no lookup finds, one that is not a constraint on a data type, a cycle, nesting
     * past the limit, an element inside a slice and a data-type profile ancestor no lookup finds are
     * reported as diagnostics and not enforced. Extension profiles are left to extension validation.
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
        $sourceUrl  = (string) ($structureDefinition['url'] ?? '');

        foreach (self::differentialElements($structureDefinition) as $id => $element) {
            $path = (string) ($element['path'] ?? '');
            if (!str_contains($path, '.') || ElementDefinitionReader::hasContentReference($element)) {
                continue;
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
                continue;
            }

            // Where the element sits: its own id, and inside a data-type profile also where that
            // profile is inlined, so a nested diagnostic can be traced back
            $where       = $trail === '' ? $id : "{$id} in {$sourceUrl}, inlined at {$trail}";
            $named       = implode(', ', array_merge(...$profiled));
            $notEnforced = static function(string $reason) use (&$diagnostics, $groupUrl, $id, $where, $named): void {
                $diagnostics[] = new CompilerDiagnostic(
                    "Profile {$groupUrl}: {$where} must conform to {$named}, but {$reason}; those rules are not enforced",
                    $id,
                    ['profileUrl' => $groupUrl, 'typeProfile' => $named],
                );
            };

            $propertyPath = self::differentialPropertyPath($element);
            if ($propertyPath === null) {
                $notEnforced('the element is inside a slice');
                continue;
            }
            if (count($types) > 1 || count($profiled[0]) > 1) {
                $notEnforced('the element allows several types or profiles, so a value may match any one of them');
                continue;
            }

            $url = self::canonicalWithoutVersion($profiled[0][0]);
            if (isset($visited[$url])) {
                $notEnforced("{$url} is already being inlined on this path, so the profiles refer to each other");
                continue;
            }
            if ($depth > self::MAX_INLINE_DEPTH) {
                $notEnforced('data-type profiles nest more than ' . self::MAX_INLINE_DEPTH . ' deep');
                continue;
            }

            // A profile that restates the type its parent already gave the element would report each
            // violation twice, once in each group; the parent's group already enforces it
            if ($depth === 1 && self::parentTypesElementTheSame($structureDefinition, $id, $profiled[0], $lookup)) {
                continue;
            }

            $definition = $lookup($url);
            if ($definition === null) {
                $notEnforced('no StructureDefinition with that URL is available');
                continue;
            }
            if (($definition['kind'] ?? null) !== 'complex-type' || ($definition['derivation'] ?? null) !== 'constraint') {
                $notEnforced('it is not a constraint on a data type');
                continue;
            }

            $at = $trail === '' ? $propertyPath : "{$trail}.{$propertyPath}";

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
                    ...self::differentialSliceConstraints($current, $groupUrl, $diagnostics),
                    ...self::differentialInvariants($current, [$groupUrl], $lookup, $diagnostics),
                    ...self::differentialBindings($current, [$groupUrl], $lookup, $diagnostics),
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

            foreach (self::mergeInheritedSlices(array_merge(...($chain === [] ? [[]] : $chain))) as $rule) {
                $attributes[] = self::underPath($rule, $propertyPath);
            }
        }

        return $attributes;
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

        $derived['discriminatorValue'] ??= $ancestor['discriminatorValue'] ?? null;

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
        $base = is_string($structureDefinition['baseDefinition'] ?? null) ? self::canonicalWithoutVersion($structureDefinition['baseDefinition']) : '';
        if ($base === '') {
            return false;
        }

        $parent = $lookup($base);
        // A base resource or data type types no element with a profile
        if ($parent === null || ($parent['derivation'] ?? null) !== 'constraint') {
            return false;
        }

        // Only a parent that allows exactly this one profiled type enforced it; one that allowed
        // several types left the element unenforced, so the narrowed child must enforce it itself
        $element = self::snapshotElements($parent)[$id] ?? null;
        $types   = is_array($element['type'] ?? null) ? array_values($element['type']) : [];
        if (count($types) !== 1 || !is_array($types[0]['profile'] ?? null)) {
            return false;
        }
        $inherited = array_values(array_map(self::canonicalWithoutVersion(...), array_filter($types[0]['profile'], 'is_string')));

        return $inherited === array_map(self::canonicalWithoutVersion(...), $profiles);
    }

    /**
     * The same rule, applied to the element at $prefix instead of the data type's root.
     */
    private static function underPath(CompiledAttribute $attribute, string $prefix): CompiledAttribute
    {
        $arguments = $attribute->arguments;
        $key       = array_key_exists('path', $arguments) ? 'path' : 'property';
        if (is_string($arguments[$key] ?? null)) {
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
     * One FHIRProfileInvariant per invariant a differential element declares.
     *
     * An invariant on the root element applies to the profiled type itself and gets an empty path.
     * Skipped invariants:
     *  - Those with no FHIRPath expression (XPath only)
     *  - Those another definition declares (`source` names it), as the base model generator skips
     *    them: that definition's own class or group enforces them
     *  - Those the base type already declares on the same element, found through $lookup, which
     *    a differential may restate
     *  - Those on an element inside a slice, which are reported as diagnostics
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param list<string>|null                                    $groups              Validation groups; null for the default group
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds the base type's definition
     * @param list<CompilerDiagnostic>                             $diagnostics
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialInvariants(array $structureDefinition, ?array $groups, ?\Closure $lookup, array &$diagnostics): array
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
            $inherited    = null;

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
     *  - Those on an element inside a slice, which are reported as diagnostics
     *
     * @param array<string, mixed>                                 $structureDefinition
     * @param list<string>|null                                    $groups              Validation groups; null for the default group
     * @param (\Closure(string): (array<string, mixed>|null))|null $lookup              Finds the base type's definition
     * @param list<CompilerDiagnostic>                             $diagnostics
     *
     * @return list<CompiledAttribute>
     */
    private static function differentialBindings(array $structureDefinition, ?array $groups, ?\Closure $lookup, array &$diagnostics): array
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
