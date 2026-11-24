<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\CanonicalUrl;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistryInterface;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResourceType as R4ResourceType;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;

/**
 * Finds the type a target profile constrains, so a literal `Patient/1` reference can be compared
 * with `Reference(au-core-patient)` without resolving the reference.
 *
 * Three sources are asked in turn, and the first that knows the profile answers:
 *
 * 1. The IG type registry: the generated class registered for the URL, read through its
 *    `#[FHIRProfile]` attribute.
 * 2. The core profiles generated with the models (`vitalsigns`, `bmi`, `cqf-questionnaire` ...),
 *    found by scanning each release's `Profile` directory once.
 * 3. The runtime StructureDefinition registry, which reads `StructureDefinition.type`. This needs
 *    the release, because one URL can be registered for several.
 *
 * A profile none of them knows has no base type, and the caller treats it as "could be anything".
 *
 * {@see FHIRTargetProfileValidator} takes one;
 * FHIRValidationServiceBuilder and the bundle build it with their IG type registry and runtime
 * profiles. Built with no arguments, it knows the generated core profiles only.
 *
 * @author Ardenexal
 */
final class TargetProfileBaseTypes
{
    /** The releases generated with the models; each has its classes under `Models\<release>\Profile`. */
    private const array MODEL_RELEASES = ['R4', 'R4B', 'R5'];

    /** @var array<string, array<string, string>>|null release => profile URL => base type, built on first use */
    private ?array $coreProfiles = null;

    /** @var array<string, string|null> "release|url" => base type, so each URL is looked up once */
    private array $cache = [];

    public function __construct(
        private readonly ?FHIRIGTypeRegistryInterface $igTypes = null,
        private readonly ?RuntimeProfileResolver $runtimeProfiles = null,
    ) {
    }

    /**
     * The type the profile constrains, such as `Patient`, or null when no source knows the profile.
     *
     * @param string      $profileUrl  Canonical URL, optionally with a `|version` suffix
     * @param string|null $fhirVersion Release of the resource holding the reference ('R4', 'R4B', 'R5'),
     *                                 or null when it cannot be told; the runtime registry is then skipped
     */
    public function baseTypeOf(string $profileUrl, ?string $fhirVersion): ?string
    {
        $key = ($fhirVersion ?? '') . '|' . $profileUrl;
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        $canonical = CanonicalUrl::stripVersion($profileUrl);

        return $this->cache[$key] = $this->fromIgTypes($canonical)
            ?? $this->fromCoreProfiles($canonical, $fhirVersion)
            ?? ($fhirVersion === null ? null : $this->runtimeProfiles?->baseTypeOf($profileUrl, $fhirVersion));
    }

    private function fromIgTypes(string $canonical): ?string
    {
        $class = $this->igTypes?->resolveProfileClass($canonical);
        if ($class === null || !class_exists($class)) {
            return null;
        }

        for ($current = new \ReflectionClass($class); $current !== false; $current = $current->getParentClass()) {
            foreach ($current->getAttributes(FHIRProfile::class) as $attribute) {
                $profile = $attribute->newInstance();
                if ($profile->profileUrl === $canonical && $profile->baseType !== '') {
                    return $profile->baseType;
                }
            }
        }

        return null;
    }

    /**
     * The base type a generated core profile declares: the given release's first, then any release
     * that agrees. Releases disagreeing on a URL's type give no answer.
     */
    private function fromCoreProfiles(string $canonical, ?string $fhirVersion): ?string
    {
        $index = $this->coreProfiles ??= self::indexCoreProfiles();

        $release = $fhirVersion === null ? null : FhirReleaseLabel::normalize($fhirVersion);
        if ($release !== null && isset($index[$release][$canonical])) {
            return $index[$release][$canonical];
        }

        $types = [];
        foreach ($index as $profiles) {
            if (isset($profiles[$canonical])) {
                $types[$profiles[$canonical]] = true;
            }
        }

        return count($types) === 1 ? array_key_first($types) : null;
    }

    /**
     * Read the `#[FHIRProfile]` attribute of every class in each release's `Profile` directory.
     *
     * @return array<string, array<string, string>>
     */
    private static function indexCoreProfiles(): array
    {
        $enumFile = (new \ReflectionClass(R4ResourceType::class))->getFileName();
        if ($enumFile === false) {
            return [];
        }

        // Models/src/R4/Enum/ResourceType.php -> Models/src
        $modelsRoot = dirname($enumFile, 3);
        $namespace  = substr(R4ResourceType::class, 0, (int) strrpos(R4ResourceType::class, '\\R4\\'));

        $index = [];
        foreach (self::MODEL_RELEASES as $release) {
            $files = glob($modelsRoot . '/' . $release . '/Profile/*.php');
            foreach ($files === false ? [] : $files as $file) {
                $class = $namespace . '\\' . $release . '\\Profile\\' . basename($file, '.php');
                if (!class_exists($class)) {
                    continue;
                }

                foreach ((new \ReflectionClass($class))->getAttributes(FHIRProfile::class) as $attribute) {
                    $profile = $attribute->newInstance();
                    if ($profile->baseType !== '') {
                        $index[$release][$profile->profileUrl] = $profile->baseType;
                    }
                }
            }
        }

        return $index;
    }
}
