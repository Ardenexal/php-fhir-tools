<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Support\CanonicalUrl;
use Ardenexal\FHIRTools\Component\CodeGeneration\Support\StringCase;

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
    private readonly CompiledProfileAttributeEmitter $attributes;

    public function __construct(StructureDefinitionCompiler $compiler = new StructureDefinitionCompiler())
    {
        $this->attributes = new CompiledProfileAttributeEmitter($compiler);
    }

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

        $className = $this->resolveProfileClassName($url, $name);

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
        $parentFqcn = $this->resolveParentFqcn($baseDefinitionUrl, $version, $context);
        $class->setExtends('\\' . $parentFqcn);
        $namespace->addUse($parentFqcn);

        // Add the canonical profile URL as a typed constant for runtime use
        $class->addConstant('PROFILE_URL', $url)
            ->setType('string')
            ->addComment('Canonical URL of this profile\'s StructureDefinition.');

        // Validation rules, from the compiler that runtime validation uses too
        $this->attributes->emit($structureDefinition, $class, $namespace, $context, $errorCollector);

        return $class;
    }

    /**
     * Derive the PHP class name for a profile.
     *
     * Resources get a "Profile" suffix (e.g. AUCorePatientProfile).
     * Complex types also get "Profile" (e.g. AUCoreHumanNameProfile).
     *
     * The canonical URL is passed through because `name` is not unique within a package and
     * {@see ClassNameResolver::DEFINITION_TO_CLASS_OVERRIDES} is keyed on the URL to settle exactly
     * that. Passing the empty string here meant no profile could ever match an override, so a
     * collision had no way to be resolved and the last definition written simply won -- by
     * enumeration order, which differs between machines. R4B's five lipid profiles all declare
     * `name: "Example Lipid Profile"`, so four of them were never generated at all.
     */
    private function resolveProfileClassName(string $url, string $name): string
    {
        $base = ClassNameResolver::resolveClassName($url, $name);

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
     * The version suffix is stripped before any lookup: the context indexes definitions under
     * their bare canonical URL, so a published IG's versioned `baseDefinition`
     * (`.../StructureDefinition/Endpoint|4.0.1`) misses every index and falls through to the
     * fallback, which then pascal-cases the suffix into the class name (`Endpoint401Resource`).
     * {@see CanonicalUrl} for why that failure mode is worth guarding explicitly.
     *
     * The fallback FQCN is only returned when the class actually exists. Emitting a name that
     * cannot be loaded turns a clear generation-time error into a confusing downstream one —
     * PHPStan reports it as a severe error and aborts the consuming project's entire analysis,
     * which hides every other finding in the generated tree.
     *
     * @throws GenerationException When the base definition cannot be resolved to a real class
     */
    private function resolveParentFqcn(string $baseDefinitionUrl, string $version, BuilderContext $context): string
    {
        $bareUrl = CanonicalUrl::stripVersion($baseDefinitionUrl);

        // Try types first (covers DataType, Primitive, and IG profiles)
        $info = $context->getType($bareUrl);
        if ($info !== null) {
            return ltrim($info->fqcn, '\\');
        }

        // Try resources
        $resourceInfo = $context->getResource($bareUrl);
        if ($resourceInfo !== null) {
            return ltrim($resourceInfo->fqcn, '\\');
        }

        // Fallback: derive class name from the bare URL segment
        $segment      = (string) u($bareUrl)->afterLast('/');
        $baseNs       = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
        $className    = StringCase::pascal($segment);
        $fallbackFqcn = "{$baseNs}\\Resource\\{$className}Resource";

        if (!class_exists($fallbackFqcn)) {
            throw GenerationException::unresolvableBaseDefinition($baseDefinitionUrl, $fallbackFqcn);
        }

        return $fallbackFqcn;
    }
}
