<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\ObligationExtensionParser;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Validator\Constraints\Count;

use function Symfony\Component\String\u;

/**
 * Generates typed PHP profile classes from FHIR constraint StructureDefinitions.
 *
 * A FHIR profile is a StructureDefinition with:
 *   - derivation: "constraint"
 *   - kind: "resource" or "complex-type"
 *   - type != "Extension"  (extension profiles are handled by FHIRExtensionGenerator)
 *
 * The generated class subclasses the base resource or type, adds a PROFILE_URL constant,
 * and carries a #[FHIRProfile] attribute for runtime introspection.
 *
 * Multi-level inheritance is supported: if the baseDefinition URL resolves to an IG-generated
 * profile class already registered in the BuilderContext (e.g. AUBasePatientProfile), the
 * generated class will extend that profile class rather than the base resource. This allows
 * chaining like:
 *
 *   PatientResource
 *     └── AUBasePatientProfile  (au.base package)
 *           └── AUCorePatientProfile  (au.core package)
 *
 * Example output for AUCorePatientProfile:
 * <pre>
 * #[FHIRProfile(profileUrl: '...', baseType: 'Patient', fhirVersion: 'R4')]
 * class AUCorePatientProfile extends AUBasePatientProfile
 * {
 *     public const string PROFILE_URL = 'http://...';
 * }
 * </pre>
 *
 * @author Ardenexal
 */
class FHIRProfileGenerator
{
    /**
     * Generate a typed profile class from a FHIR constraint StructureDefinition.
     *
     * @param array<string, mixed> $structureDefinition StructureDefinition with derivation=constraint
     * @param string               $version             FHIR version (e.g. 'R4')
     * @param BuilderContext       $context             Builder context for parent class resolution
     * @param PhpNamespace         $namespace           Target namespace for the generated class
     * @param ErrorCollector|null  $errorCollector      Optional collector for unresolvable-type warnings
     *
     * @return ClassType The generated PHP class
     *
     * @throws \RuntimeException When the base definition cannot be resolved to a PHP class
     */
    public function generate(
        array $structureDefinition,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        ?ErrorCollector $errorCollector = null,
    ): ClassType {
        $url               = $structureDefinition['url']            ?? '';
        $name              = $structureDefinition['name']           ?? 'UnknownProfile';
        $baseType          = $structureDefinition['type']           ?? '';
        $baseDefinitionUrl = $structureDefinition['baseDefinition'] ?? '';
        $kind              = $structureDefinition['kind']           ?? 'resource';

        $className = $this->resolveProfileClassName($name, $kind);

        $class = new ClassType($className, $namespace);
        $class->addAttribute(FHIRProfile::class, [
            'profileUrl'  => $url,
            'baseType'    => $baseType,
            'fhirVersion' => $version,
        ]);

        if (isset($structureDefinition['publisher'])) {
            $class->addComment('@author ' . $structureDefinition['publisher']);
        }
        $class->addComment('@see ' . $url);
        if (!empty($structureDefinition['description'])) {
            $class->addComment('@description ' . $structureDefinition['description']);
        }

        // Resolve the parent class — may be a base FHIR type or an IG profile class
        $parentFqcn = $this->resolveParentFqcn($baseDefinitionUrl, $version, $context, $errorCollector);
        $class->setExtends('\\' . $parentFqcn);
        $namespace->addUse($parentFqcn);

        // Add the canonical profile URL as a typed constant for runtime use
        $class->addConstant('PROFILE_URL', $url)
            ->setType('string')
            ->addComment('Canonical URL of this profile\'s StructureDefinition.');

        $this->emitDifferentialConstraints($structureDefinition, $url, $class, $namespace);
        $this->emitDifferentialMustSupport($structureDefinition, $url, $class, $namespace);
        $this->emitDifferentialSliceConstraints($structureDefinition, $url, $class, $namespace);
        $this->emitSnapshotObligations($structureDefinition, $url, $class, $namespace);

        return $class;
    }

    /**
     * Emits #[FHIRProfileConstraint] attributes on the class for each differential element that
     * carries a cardinality (min/max), fixed[x], or pattern[x] constraint. The profile URL is
     * used as the Symfony validation group so these constraints are only active when that profile
     * is requested.
     *
     * Skipped elements:
     *  - Root element (path has no '.' — it's the resource or type itself, not a property)
     *  - Elements with contentReference (constraint lives on the referenced type)
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function emitDifferentialConstraints(
        array $structureDefinition,
        string $profileUrl,
        ClassType $class,
        PhpNamespace $namespace,
    ): void {
        /** @var array<int, array<string, mixed>> $elements */
        $elements = $structureDefinition['differential']['element'] ?? [];

        foreach ($elements as $element) {
            $path = (string) ($element['path'] ?? '');

            // Skip root element (e.g. "Patient") — no property path to map
            if (!str_contains($path, '.')) {
                continue;
            }

            // Skip contentReference elements — constraints live on the referenced type
            if (ElementDefinitionHelper::hasContentReference($element)) {
                continue;
            }

            // Extract the property path: strip the resource/type prefix ("Patient.name" → "name")
            $dotPos        = strpos($path, '.');
            $propertyPath  = $dotPos !== false ? substr($path, $dotPos + 1) : $path;

            // Cardinality constraint (Count) — emitted when min > 0 or max is a bounded number
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
                $namespace->addUse(Count::class);
                $namespace->addUse(FHIRProfileConstraint::class);
                $class->addAttribute(FHIRProfileConstraint::class, [
                    'path'       => $propertyPath,
                    'constraint' => Count::class,
                    'options'    => $countOptions,
                    'groups'     => [$profileUrl],
                ]);
            }

            // Fixed value constraint (scalar values only — complex fixed[x] require profile resolution)
            $fixedField = ElementDefinitionHelper::extractPolymorphicField($element, 'fixed');
            if ($fixedField !== null && is_scalar($fixedField['value'])) {
                $namespace->addUse(FHIRFixedValue::class);
                $namespace->addUse(FHIRProfileConstraint::class);
                $class->addAttribute(FHIRProfileConstraint::class, [
                    'path'       => $propertyPath,
                    'constraint' => FHIRFixedValue::class,
                    'options'    => ['value' => $fixedField['value']],
                    'groups'     => [$profileUrl],
                ]);
            }

            // Pattern value constraint (array values only — scalar patterns use value matching)
            $patternField = ElementDefinitionHelper::extractPolymorphicField($element, 'pattern');
            if ($patternField !== null && is_array($patternField['value'])) {
                $namespace->addUse(FHIRPatternValue::class);
                $namespace->addUse(FHIRProfileConstraint::class);
                $class->addAttribute(FHIRProfileConstraint::class, [
                    'path'       => $propertyPath,
                    'constraint' => FHIRPatternValue::class,
                    'options'    => ['pattern' => $patternField['value']],
                    'groups'     => [$profileUrl],
                ]);
            }
        }
    }

    /**
     * Emits #[FHIRProfileMustSupport] class-level attributes for each differential element that
     * declares mustSupport=true. Profile classes cannot re-declare inherited properties, so the
     * must-support information is carried at the class level as a pure metadata attribute
     * (no Symfony Validator involvement).
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function emitDifferentialMustSupport(
        array $structureDefinition,
        string $profileUrl,
        ClassType $class,
        PhpNamespace $namespace,
    ): void {
        /** @var array<int, array<string, mixed>> $elements */
        $elements = $structureDefinition['differential']['element'] ?? [];

        foreach ($elements as $element) {
            if (($element['mustSupport'] ?? false) !== true) {
                continue;
            }

            $path = (string) ($element['path'] ?? '');

            // Skip root element (e.g. "Patient") — no property path to map
            if (!str_contains($path, '.')) {
                continue;
            }

            $dotPos       = strpos($path, '.');
            $propertyPath = $dotPos !== false ? substr($path, $dotPos + 1) : $path;

            $namespace->addUse(FHIRProfileMustSupport::class);
            $class->addAttribute(FHIRProfileMustSupport::class, [
                'path'   => $propertyPath,
                'groups' => [$profileUrl],
            ]);
        }
    }

    /**
     * Emits #[FHIRSliceConstraint] and #[FHIRSlicingRules] attributes on the class for each
     * property that carries FHIR slicing in the profile differential.
     *
     * Slice detection algorithm:
     *   1. Collect all elements with a `slicing` key → these are the sliced properties.
     *   2. For each sliced property, collect sibling elements with a matching `sliceName` →
     *      these are the individual slices.
     *   3. For each slice, extract the discriminator value by walking child elements:
     *      find the element whose path tail matches the discriminator path and extract
     *      its fixed[x] or pattern[x] value.
     *   4. Emit one #[FHIRSliceConstraint] per slice and one #[FHIRSlicingRules] per property.
     *
     * Limitations:
     *   - Only the first discriminator is used when a slicing entry declares multiple
     *     discriminators (composite discriminators are not yet supported).
     *   - Discriminator types 'type' and 'profile' are recorded but the matcher will emit
     *     a warning and return false for them (they require full profile resolution).
     *   - Slicing on root elements (no '.') is skipped.
     *   - Elements with contentReference are skipped.
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function emitDifferentialSliceConstraints(
        array $structureDefinition,
        string $profileUrl,
        ClassType $class,
        PhpNamespace $namespace,
    ): void {
        /** @var array<int, array<string, mixed>> $elements */
        $elements = $structureDefinition['differential']['element'] ?? [];

        // Index all elements by their element id (or path+sliceName) for child lookups
        // Build a map: basePropertyPath → slicingDefinition
        /** @var array<string, array<string, mixed>> $slicingByProperty propertyPath → slicing def */
        $slicingByProperty = [];
        /** @var array<string, array<int, array<string, mixed>>> $slicesByProperty propertyPath → slice elements */
        $slicesByProperty  = [];

        $resourceType = $structureDefinition['type'] ?? '';

        foreach ($elements as $element) {
            $path = (string) ($element['path'] ?? '');

            // Skip root element
            if (!str_contains($path, '.')) {
                continue;
            }

            // Skip contentReference elements
            if (ElementDefinitionHelper::hasContentReference($element)) {
                continue;
            }

            // Strip resource-type prefix ("Patient.identifier" → "identifier")
            $dotPos       = strpos($path, '.');
            $propertyPath = $dotPos !== false ? substr($path, $dotPos + 1) : $path;

            if (isset($element['slicing'])) {
                // Base element of a sliced property (e.g. "Patient.identifier")
                $slicingByProperty[$propertyPath] = $element['slicing'];
            }

            $sliceName = $element['sliceName'] ?? null;
            if ($sliceName !== null && $sliceName !== '') {
                // A named slice definition (e.g. "Patient.identifier" with sliceName "ihiNumber")
                // The path equals the parent path (e.g. both are "Patient.identifier")
                // In FHIR differential, sliced elements share the same path (e.g. "Patient.identifier")
                // and differ only by sliceName, so the base path is the property path unchanged.
                $slicesByProperty[$propertyPath][] = $element;
            }
        }

        // For each sliced property, emit attributes
        foreach ($slicingByProperty as $propertyPath => $slicingDef) {
            $discriminators = $slicingDef['discriminator'] ?? [];
            $rules          = (string) ($slicingDef['rules'] ?? 'open');
            $slices         = $slicesByProperty[$propertyPath] ?? [];

            if ($slices === []) {
                continue;
            }

            // Log a warning for composite discriminators (only first is used)
            if (count($discriminators) > 1) {
                // Composite discriminators are not yet supported — use only the first
                $discriminators = [$discriminators[0]];
            }

            $discriminator = $discriminators[0] ?? null;
            $discType      = (string) ($discriminator['type'] ?? 'value');
            $discPath      = (string) ($discriminator['path'] ?? '');

            // Emit #[FHIRSlicingRules] for this property
            $namespace->addUse(FHIRSlicingRules::class);
            $class->addAttribute(FHIRSlicingRules::class, [
                'property' => $propertyPath,
                'rules'    => $rules,
                'groups'   => [$profileUrl],
            ]);

            // Emit one #[FHIRSliceConstraint] per slice
            foreach ($slices as $orderedIndex => $sliceElement) {
                $sliceName = (string) ($sliceElement['sliceName'] ?? '');
                $min       = (int) ($sliceElement['min'] ?? 0);
                $max       = (string) ($sliceElement['max'] ?? '*');
                $isDefault = $sliceName === '@default';

                // Extract discriminator value from child elements
                $discValue = $this->extractDiscriminatorValue(
                    $elements,
                    $resourceType,
                    $propertyPath,
                    $sliceName,
                    $discType,
                    $discPath,
                    $sliceElement,
                );

                $namespace->addUse(FHIRSliceConstraint::class);
                $attrArgs = [
                    'property'          => $propertyPath,
                    'sliceName'         => $sliceName,
                    'min'               => $min,
                    'max'               => is_numeric($max) ? (int) $max : $max,
                    'discriminatorType' => $discType,
                    'discriminatorPath' => $discPath,
                    'groups'            => [$profileUrl],
                    'orderedIndex'      => $orderedIndex,
                ];

                if ($discValue !== null) {
                    $attrArgs['discriminatorValue'] = $discValue;
                }

                if ($isDefault) {
                    $attrArgs['isDefault'] = true;
                }

                $class->addAttribute(FHIRSliceConstraint::class, $attrArgs);
            }
        }
    }

    /**
     * Extract the discriminator value for a given slice by scanning child elements.
     *
     * For 'value' and 'pattern' discriminators, the value lives in a child element whose
     * path tail matches the discriminator path, e.g.:
     *   Parent: "Patient.identifier" discriminator path="system"
     *   Slice header: "Patient.identifier" sliceName="ihiNumber"
     *   Child: "Patient.identifier.system" (sliceName="ihiNumber") with fixedUri
     *
     * For 'value' discriminator on 'url' (extension slicing), the value is usually the
     * extension profile URL from the slice element's type[0].profile[0].
     *
     * Returns null if the value cannot be determined without full profile resolution.
     *
     * @param array<int, array<string, mixed>> $allElements  All differential elements
     * @param string                           $resourceType Resource or complex type (e.g. 'Patient')
     * @param string                           $propertyPath Sliced property path (e.g. 'identifier')
     * @param string                           $sliceName    Named slice (e.g. 'ihiNumber')
     * @param string                           $discType     Discriminator type
     * @param string                           $discPath     Discriminator path (e.g. 'system', 'url')
     * @param array<string, mixed>             $sliceElement The slice header element
     */
    private function extractDiscriminatorValue(
        array $allElements,
        string $resourceType,
        string $propertyPath,
        string $sliceName,
        string $discType,
        string $discPath,
        array $sliceElement,
    ): mixed {
        // For 'value' or 'pattern' discriminators: look for a child element at the discriminator path
        if (in_array($discType, ['value', 'pattern'], true) && $discPath !== '' && $discPath !== '$this') {
            $targetPath = "{$resourceType}.{$propertyPath}.{$discPath}";

            foreach ($allElements as $element) {
                $path      = (string) ($element['path'] ?? '');
                $elemSlice = (string) ($element['sliceName'] ?? '');

                if ($path !== $targetPath) {
                    continue;
                }

                // Must belong to the same slice
                if ($elemSlice !== $sliceName && $elemSlice !== '') {
                    continue;
                }

                // Extract fixed[x] value
                $fixed = ElementDefinitionHelper::extractPolymorphicField($element, 'fixed');
                if ($fixed !== null) {
                    return $fixed['value'];
                }

                // Extract pattern[x] value
                $pattern = ElementDefinitionHelper::extractPolymorphicField($element, 'pattern');
                if ($pattern !== null) {
                    return $pattern['value'];
                }
            }

            // Special case: value discriminator on 'url' (extension slicing)
            // The URL is the extension profile URL from the slice element itself
            if ($discPath === 'url') {
                $profile = $sliceElement['type'][0]['profile'][0] ?? null;
                if ($profile !== null) {
                    return $profile;
                }
            }
        }

        // For 'exists' discriminator: the boolean value comes from the slicing definition
        // (not standard practice to put it in the differential, so return null for resolution)

        return null;
    }

    /**
     * Emits #[FHIRProfileObligation] class-level attributes for each snapshot element that carries
     * obligation extensions (http://hl7.org/fhir/StructureDefinition/obligation).
     *
     * Obligations appear in snapshot (not differential) elements per the FHIR specification.
     * Profile classes cannot re-declare inherited constructor parameters, so all obligation
     * metadata is carried at the class level as repeatable marker attributes.
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function emitSnapshotObligations(
        array $structureDefinition,
        string $profileUrl,
        ClassType $class,
        PhpNamespace $namespace,
    ): void {
        /** @var array<int, array<string, mixed>> $elements */
        $elements = $structureDefinition['snapshot']['element'] ?? [];

        if ($elements === []) {
            return;
        }

        $parser = new ObligationExtensionParser();

        foreach ($elements as $element) {
            $path = (string) ($element['path'] ?? '');

            // Skip root element (e.g. "Patient") — no property path to map
            if (!str_contains($path, '.')) {
                continue;
            }

            // Skip contentReference elements — obligations live on the referenced type
            if (ElementDefinitionHelper::hasContentReference($element)) {
                continue;
            }

            $obligations = $parser->parse($element['extension'] ?? []);

            if ($obligations === []) {
                continue;
            }

            $dotPos       = strpos($path, '.');
            $propertyPath = $dotPos !== false ? substr($path, $dotPos + 1) : $path;

            $namespace->addUse(FHIRProfileObligation::class);

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

                $class->addAttribute(FHIRProfileObligation::class, $args);
            }
        }
    }

    /**
     * Derive the PHP class name for a profile.
     *
     * Resources get a "Profile" suffix (e.g. AUCorePatientProfile).
     * Complex types also get "Profile" (e.g. AUCoreHumanNameProfile).
     */
    private function resolveProfileClassName(string $name, string $kind): string
    {
        $base = ClassNameResolver::resolveClassName('', $name);

        // If the IG already appended "Profile" to the name, avoid doubling it
        if (str_ends_with($base, 'Profile')) {
            return $base;
        }

        return $base . 'Profile';
    }

    /**
     * Resolve the FQCN of the parent class for a profile.
     *
     * Lookup order:
     *   1. BuilderContext types — covers base FHIR types AND previously-generated IG profiles
     *   2. BuilderContext resources — covers FHIR resource types
     *   3. Fallback: construct FQCN from the FHIR type name using known namespace conventions
     *
     * When the fallback is used, a warning is recorded via the ErrorCollector so the caller
     * can surface it to the user.
     *
     * @throws \RuntimeException When the base definition cannot be resolved
     */
    private function resolveParentFqcn(string $baseDefinitionUrl, string $version, BuilderContext $context, ?ErrorCollector $errorCollector = null): string
    {
        // Try types first (covers DataType, Primitive, and IG profiles)
        $info = $context->getType($baseDefinitionUrl);
        if ($info !== null) {
            return ltrim($info->fqcn, '\\');
        }

        // Try resources
        $resourceInfo = $context->getResource($baseDefinitionUrl);
        if ($resourceInfo !== null) {
            return ltrim($resourceInfo->fqcn, '\\');
        }

        // Fallback: derive class name from the URL segment
        $segment      = (string) u($baseDefinitionUrl)->afterLast('/');
        $baseNs       = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
        $className    = u($segment)->pascal()->toString();
        $fallbackFqcn = "{$baseNs}\\Resource\\{$className}Resource";

        $errorCollector?->addWarning(
            "Could not resolve baseDefinition URL '{$baseDefinitionUrl}' — using fallback FQCN "
            . "'{$fallbackFqcn}'. Ensure the package providing this type is included in your --package list.",
            $baseDefinitionUrl,
        );

        // Heuristic: if it looks like a resource (starts with uppercase and is known FHIR kind),
        // put it in Resource/ with the Resource suffix. Otherwise DataType/.
        return $fallbackFqcn;
    }
}
