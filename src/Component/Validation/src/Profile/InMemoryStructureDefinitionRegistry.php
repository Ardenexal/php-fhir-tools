<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Registry for StructureDefinitions you already hold as decoded JSON.
 *
 * Each definition is stored under its FHIR release, canonical URL and business version. A lookup
 * without a version returns the highest business version, compared with `version_compare()`;
 * adding a definition whose version is already registered replaces it.
 *
 * @author Ardenexal
 */
final class InMemoryStructureDefinitionRegistry implements StructureDefinitionRegistryInterface
{
    /**
     * Definitions by release label, then URL, then business version ('' when unversioned).
     *
     * @var array<string, array<string, array<string, array<string, mixed>>>>
     */
    private array $definitions = [];

    /**
     * @param list<array<string, mixed>> $structureDefinitions Definitions to add, each carrying its own `fhirVersion`
     */
    public function __construct(array $structureDefinitions = [])
    {
        foreach ($structureDefinitions as $structureDefinition) {
            $this->add($structureDefinition);
        }
    }

    /**
     * Add one StructureDefinition.
     *
     * @param array<string, mixed> $structureDefinition Decoded StructureDefinition JSON
     * @param string|null          $fhirVersion         Release label or spec version; null reads the definition's `fhirVersion`
     *
     * @throws \InvalidArgumentException When the definition has no URL, or its FHIR release cannot be determined
     */
    public function add(array $structureDefinition, ?string $fhirVersion = null): void
    {
        $url = $structureDefinition['url'] ?? null;
        if (!is_string($url) || $url === '') {
            throw new \InvalidArgumentException('A StructureDefinition needs a non-empty "url" to be registered.');
        }

        $declared = $fhirVersion ?? $structureDefinition['fhirVersion'] ?? null;
        $release  = is_string($declared) ? FhirReleaseLabel::normalize($declared) : null;
        if ($release === null) {
            throw new \InvalidArgumentException(sprintf('Cannot tell which FHIR release StructureDefinition "%s" belongs to: give its fhirVersion (R4, R4B or R5).', $url));
        }

        $version = $structureDefinition['version'] ?? '';

        $this->definitions[$release][$url][is_string($version) ? $version : ''] = $structureDefinition;
    }

    public function find(string $fhirVersion, string $url, ?string $version = null): ?array
    {
        $release   = FhirReleaseLabel::normalize($fhirVersion);
        $byVersion = $release === null ? [] : ($this->definitions[$release][$url] ?? []);
        if ($byVersion === []) {
            return null;
        }

        if ($version !== null) {
            return $byVersion[$version] ?? null;
        }

        return $byVersion[BusinessVersion::latest(array_keys($byVersion))];
    }

    public function fhirVersionsFor(string $url): array
    {
        $releases = [];
        foreach ($this->definitions as $release => $byUrl) {
            if (isset($byUrl[$url])) {
                $releases[] = $release;
            }
        }

        return $releases;
    }
}
