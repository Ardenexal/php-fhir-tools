<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;

/**
 * Turns a profile URL into the compiled rules of that profile and every constraint ancestor.
 *
 * A generated profile class inherits its parent profile's attributes. At runtime there is no
 * class, so the resolver walks `baseDefinition` instead: it compiles the requested profile, then
 * its parent, and so on while `derivation` is `constraint`, stopping at the first specialization
 * (the base resource or data type). Each StructureDefinition is compiled with the same compiler
 * the generator uses, under its own URL, so each ancestor's rules fire in its own group exactly
 * as they do on a generated class.
 *
 * Every StructureDefinition in the chain must carry a snapshot: snapshots are never generated.
 *
 * Compiled profiles are memoised by FHIR release, URL and business version, so validating many
 * resources against one profile compiles it once; a definition replaced in its registry is
 * compiled again. Resolutions themselves are not cached, so a
 * failure caused by a definition that is added to the registry later does not stick.
 *
 * @author Ardenexal
 */
final class RuntimeProfileResolver
{
    /** StructureDefinition kinds runtime validation supports: resources, data types and extensions. */
    private const array SUPPORTED_KINDS = ['resource', 'complex-type'];

    /**
     * Compiled profiles by "release|url|version", each with the definition it was compiled from.
     *
     * @var array<string, array{definition: array<string, mixed>, profile: CompiledProfile}>
     */
    private array $compiled = [];

    /**
     * The profiles validation last checked each type against, by release, then root type, then URL.
     *
     * The slice validator finds a profile's sibling slices by reading attributes off the validated
     * object's class. A base instance has none, so {@see RuntimeProfileAttributeReader} looks them up
     * here instead. Filled by {@see activate()}, not by resolution, so a profile resolved but not
     * checked (a conflicting version, say) never replaces the slices of the one that is.
     *
     * @var array<string, array<string, array<string, CompiledProfile>>>
     */
    private array $resolvedByRootType = [];

    public function __construct(
        private readonly StructureDefinitionRegistryInterface $registry,
        private readonly StructureDefinitionCompiler $compiler = new StructureDefinitionCompiler(),
    ) {
    }

    /**
     * Resolve a profile URL for resources of one FHIR release.
     *
     * @param string $url         Canonical URL, optionally with a `|version` suffix
     * @param string $fhirVersion The release of the resource being validated: 'R4', 'R4B' or 'R5'
     *
     * @throws \InvalidArgumentException When $fhirVersion is not R4, R4B or R5
     */
    public function resolve(string $url, string $fhirVersion): RuntimeProfileResolution
    {
        $release = FhirReleaseLabel::normalize($fhirVersion);
        if ($release === null) {
            throw new \InvalidArgumentException(sprintf('FHIR version "%s" is not R4, R4B or R5.', $fhirVersion));
        }

        [$canonical, $version] = self::splitCanonical($url);
        $current               = $this->registry->find($release, $canonical, $version);
        if ($current === null) {
            return $this->notFound($url, $release, $canonical, $version);
        }

        $rootType = is_string($current['type'] ?? null) ? $current['type'] : '';
        $kind     = is_string($current['kind'] ?? null) ? $current['kind'] : '';

        /** @var list<CompiledProfile> $chain requested profile first, reversed on return */
        $chain       = [];
        $visited     = [];
        $currentUrl  = $canonical;

        while (true) {
            $currentKind = $current['kind'] ?? null;
            if (!in_array($currentKind, self::SUPPORTED_KINDS, true)) {
                return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::UnsupportedKind, $currentUrl, sprintf(
                    'StructureDefinition "%s" has kind "%s"; runtime validation supports resource, data type and extension definitions only.',
                    $currentUrl,
                    is_string($currentKind) ? $currentKind : '',
                ));
            }

            if (($current['derivation'] ?? null) !== 'constraint') {
                break;
            }

            $identity = $currentUrl . '|' . self::businessVersion($current);
            if (isset($visited[$identity])) {
                return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::CircularParent, $currentUrl, sprintf(
                    'Following baseDefinition from "%s" leads back to "%s".',
                    $canonical,
                    $currentUrl,
                ));
            }
            $visited[$identity] = true;

            if (!self::hasSnapshot($current)) {
                return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::NoSnapshot, $currentUrl, sprintf(
                    'StructureDefinition "%s" has no snapshot; runtime validation needs snapshots and does not generate them.',
                    $currentUrl,
                ));
            }

            $chain[] = $this->compile($release, $current);

            $parentRef = $current['baseDefinition'] ?? null;
            if (!is_string($parentRef) || $parentRef === '') {
                return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::UnresolvedParent, $currentUrl, sprintf(
                    'Constraint StructureDefinition "%s" has no baseDefinition.',
                    $currentUrl,
                ));
            }

            [$parentUrl, $parentVersion] = self::splitCanonical($parentRef);
            $parent                      = $this->registry->find($release, $parentUrl, $parentVersion);
            if ($parent === null) {
                return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::UnresolvedParent, $parentRef, sprintf(
                    'StructureDefinition "%s" derives from "%s", which no registry holds for %s.',
                    $currentUrl,
                    $parentRef,
                    $release,
                ));
            }

            $current    = $parent;
            $currentUrl = $parentUrl;
        }

        return RuntimeProfileResolution::resolved($url, $release, array_reverse($chain), $rootType, $kind);
    }

    /**
     * Record the compiled profiles a validation pass is about to check one type against.
     *
     * The slice reader serves these, so it always reads the exact versions whose constraints run.
     * A later activation of the same URL replaces the entry, so one URL never contributes two sets
     * of slices.
     *
     * @param string                $fhirVersion 'R4', 'R4B' or 'R5'
     * @param string                $rootType    FHIR type name, e.g. 'Observation'
     * @param list<CompiledProfile> $profiles
     */
    public function activate(string $fhirVersion, string $rootType, array $profiles): void
    {
        $release = FhirReleaseLabel::normalize($fhirVersion);
        if ($release === null) {
            return;
        }

        foreach ($profiles as $profile) {
            $this->resolvedByRootType[$release][$rootType][$profile->profileUrl] = $profile;
        }
    }

    /**
     * Profiles activated so far for one type, in the order they were first activated.
     *
     * @param string $fhirVersion 'R4', 'R4B' or 'R5'
     * @param string $rootType    FHIR type name, e.g. 'Observation'
     *
     * @return list<CompiledProfile>
     */
    public function resolvedProfilesFor(string $fhirVersion, string $rootType): array
    {
        $release = FhirReleaseLabel::normalize($fhirVersion);

        return $release === null ? [] : array_values($this->resolvedByRootType[$release][$rootType] ?? []);
    }

    /**
     * Compile one StructureDefinition, or return the copy compiled earlier.
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function compile(string $release, array $structureDefinition): CompiledProfile
    {
        $key = $release . '|' . (string) ($structureDefinition['url'] ?? '') . '|' . self::businessVersion($structureDefinition);

        // A registry may replace a definition under the same URL and version, so the cached
        // copy is reused only while the definition is unchanged. Registries hand back the
        // array they hold, which PHP compares by reference before comparing contents.
        $cached = $this->compiled[$key] ?? null;
        if ($cached !== null && $cached['definition'] === $structureDefinition) {
            return $cached['profile'];
        }

        $profile              = $this->compiler->compile($structureDefinition);
        $this->compiled[$key] = ['definition' => $structureDefinition, 'profile' => $profile];

        return $profile;
    }

    private function notFound(string $url, string $release, string $canonical, ?string $version): RuntimeProfileResolution
    {
        $elsewhere = array_values(array_diff($this->registry->fhirVersionsFor($canonical), [$release]));
        if ($elsewhere !== [] && $this->registry->find($release, $canonical) === null) {
            return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::FhirVersionMismatch, $canonical, sprintf(
                'Profile "%s" is registered for %s, not for %s.',
                $canonical,
                implode(', ', $elsewhere),
                $release,
            ));
        }

        return RuntimeProfileResolution::failed($url, $release, RuntimeProfileFailure::Unknown, $url, $version === null
            ? sprintf('No StructureDefinition with URL "%s" is registered for %s.', $canonical, $release)
            : sprintf('No StructureDefinition with URL "%s" and version "%s" is registered for %s.', $canonical, $version, $release));
    }

    /**
     * Split "url|version" into its parts; a URL without a suffix has a null version.
     *
     * @return array{0: string, 1: string|null}
     */
    private static function splitCanonical(string $reference): array
    {
        $pipe = strpos($reference, '|');
        if ($pipe === false) {
            return [$reference, null];
        }

        $version = substr($reference, $pipe + 1);

        return [substr($reference, 0, $pipe), $version === '' ? null : $version];
    }

    /**
     * @param array<string, mixed> $structureDefinition
     */
    private static function businessVersion(array $structureDefinition): string
    {
        $version = $structureDefinition['version'] ?? '';

        return is_string($version) ? $version : '';
    }

    /**
     * @param array<string, mixed> $structureDefinition
     */
    private static function hasSnapshot(array $structureDefinition): bool
    {
        $snapshot = $structureDefinition['snapshot'] ?? null;

        return is_array($snapshot) && is_array($snapshot['element'] ?? null) && $snapshot['element'] !== [];
    }
}
