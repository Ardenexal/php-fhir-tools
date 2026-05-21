<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
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

            // Fixed value constraint
            $fixedField = ElementDefinitionHelper::extractPolymorphicField($element, 'fixed');
            if ($fixedField !== null) {
                $namespace->addUse(FHIRFixedValue::class);
                $namespace->addUse(FHIRProfileConstraint::class);
                $class->addAttribute(FHIRProfileConstraint::class, [
                    'path'       => $propertyPath,
                    'constraint' => FHIRFixedValue::class,
                    'options'    => ['value' => $fixedField['value']],
                    'groups'     => [$profileUrl],
                ]);
            }

            // Pattern value constraint
            $patternField = ElementDefinitionHelper::extractPolymorphicField($element, 'pattern');
            if ($patternField !== null) {
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
