<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Snapshot;

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoaderInterface;
use Ardenexal\FHIRTools\Component\Validation\Exception\SnapshotGenerationException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Generates StructureDefinition snapshots by merging differentials with base definitions.
 *
 * Snapshots are required for accurate validation because they:
 * - Include all inherited constraints from baseDefinition
 * - Materialize complete slicing structures
 * - Stabilize element paths
 * - Provide correct cardinality after constraint application
 *
 * @author FHIR Tools
 */
class SnapshotGenerator
{
    private const CACHE_KEY_PREFIX = 'sd_snapshot:';

    private const CACHE_TTL = 86400; // 24 hours

    public function __construct(
        private readonly PackageLoaderInterface $packageLoader,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    /**
     * Generate or retrieve cached snapshot for a StructureDefinition.
     *
     * @param array<string, mixed> $structureDefinition The StructureDefinition
     *
     * @return array<string, mixed> The StructureDefinition with complete snapshot
     *
     * @throws SnapshotGenerationException
     */
    public function generate(array $structureDefinition): array
    {
        // Validate input
        if (!isset($structureDefinition['url'])) {
            throw new SnapshotGenerationException('StructureDefinition must have a url');
        }

        // Check cache first
        $cacheKey   = $this->getCacheKey($structureDefinition);
        $cachedItem = $this->cache->getItem($cacheKey);

        if ($cachedItem->isHit() && $this->isSnapshotCurrent($structureDefinition, $cachedItem->get())) {
            return $cachedItem->get();
        }

        // Generate snapshot if not cached or outdated
        $snapshotted = $this->generateSnapshot($structureDefinition);

        // Cache the result
        $cachedItem->set($snapshotted);
        $cachedItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cachedItem);

        return $snapshotted;
    }

    /**
     * Generate snapshot by merging differential with base definition.
     *
     * @param array<string, mixed> $sd The StructureDefinition
     *
     * @return array<string, mixed> The StructureDefinition with snapshot
     *
     * @throws SnapshotGenerationException
     */
    private function generateSnapshot(array $sd): array
    {
        // If snapshot already exists and derivation is 'specialization', use it
        if (isset($sd['snapshot']) && ($sd['derivation'] ?? null) === 'specialization') {
            return $sd;
        }

        // If no baseDefinition, this is a base resource (like Resource itself)
        if (!isset($sd['baseDefinition'])) {
            // For base resources, differential IS the snapshot
            if (isset($sd['differential'])) {
                $sd['snapshot'] = $sd['differential'];
            } else {
                throw new SnapshotGenerationException("StructureDefinition {$sd['url']} has no baseDefinition and no differential");
            }

            return $sd;
        }

        // Load base definition
        $baseDefinition = $this->loadBaseDefinition($sd['baseDefinition']);

        // Ensure base has snapshot
        if (!isset($baseDefinition['snapshot'])) {
            $baseDefinition = $this->generateSnapshot($baseDefinition);
        }

        // Clone base snapshot as starting point
        $snapshotElements = $baseDefinition['snapshot']['element'] ?? [];

        // Apply differential if present
        if (isset($sd['differential']['element'])) {
            $snapshotElements = $this->applyDifferential(
                $snapshotElements,
                $sd['differential']['element'],
                $sd['type'] ?? $baseDefinition['type'],
            );
        }

        // Set the snapshot
        $sd['snapshot'] = [
            'element' => $snapshotElements,
        ];

        return $sd;
    }

    /**
     * Apply differential elements to base snapshot elements.
     *
     * @param array<array<string, mixed>> $baseElements Base snapshot elements
     * @param array<array<string, mixed>> $diffElements Differential elements
     * @param string                      $resourceType The resource type
     *
     * @return array<array<string, mixed>> Merged snapshot elements
     */
    private function applyDifferential(array $baseElements, array $diffElements, string $resourceType): array
    {
        // Index base elements by path for quick lookup
        $baseByPath = [];
        foreach ($baseElements as $element) {
            $baseByPath[$element['path']] = $element;
        }

        // Process each differential element
        foreach ($diffElements as $diffElement) {
            $path = $diffElement['path'];

            if (isset($baseByPath[$path])) {
                // Merge with existing base element
                $baseByPath[$path] = $this->mergeElements($baseByPath[$path], $diffElement);
            } else {
                // New element (extension or slice)
                $baseByPath[$path] = $diffElement;
            }
        }

        // Return as indexed array
        return array_values($baseByPath);
    }

    /**
     * Merge differential element into base element.
     *
     * @param array<string, mixed> $base Base element
     * @param array<string, mixed> $diff Differential element
     *
     * @return array<string, mixed> Merged element
     */
    private function mergeElements(array $base, array $diff): array
    {
        // Start with base element
        $merged = $base;

        // Override/add properties from differential
        foreach ($diff as $key => $value) {
            if ($key === 'path' || $key === 'id') {
                // Always use differential path/id
                $merged[$key] = $value;
            } elseif ($key === 'min') {
                // Use more restrictive min
                $merged[$key] = max($base[$key] ?? 0, $value);
            } elseif ($key === 'max') {
                // Use more restrictive max
                $merged[$key] = $this->getMoreRestrictiveMax($base[$key] ?? '*', $value);
            } elseif ($key === 'type') {
                // Type can be constrained
                $merged[$key] = $this->mergeTypes($base[$key] ?? [], $value);
            } elseif ($key === 'binding') {
                // Merge binding (differential can strengthen)
                $merged[$key] = $this->mergeBinding($base[$key] ?? null, $value);
            } elseif ($key === 'constraint') {
                // Add constraints (accumulate)
                $merged[$key] = array_merge($base[$key] ?? [], $value);
            } elseif ($key === 'slicing') {
                // Slicing from differential
                $merged[$key] = $value;
            } else {
                // For other properties, differential overrides
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Get more restrictive max cardinality.
     */
    private function getMoreRestrictiveMax(string $base, string $diff): string
    {
        if ($base === '*') {
            return $diff;
        }

        if ($diff === '*') {
            return $base;
        }

        return (string) min((int) $base, (int) $diff);
    }

    /**
     * Merge type constraints.
     *
     * @param array<array<string, mixed>> $baseTypes
     * @param array<array<string, mixed>> $diffTypes
     *
     * @return array<array<string, mixed>>
     */
    private function mergeTypes(array $baseTypes, array $diffTypes): array
    {
        // If differential specifies types, it constrains the base
        if (!empty($diffTypes)) {
            return $diffTypes;
        }

        return $baseTypes;
    }

    /**
     * Merge binding constraints.
     *
     * @param array<string, mixed>|null $baseBinding
     * @param array<string, mixed>      $diffBinding
     *
     * @return array<string, mixed>
     */
    private function mergeBinding(?array $baseBinding, array $diffBinding): array
    {
        if ($baseBinding === null) {
            return $diffBinding;
        }

        // Differential can strengthen binding
        $strengthOrder = ['example' => 0, 'preferred' => 1, 'extensible' => 2, 'required' => 3];

        $baseStrength = $strengthOrder[$baseBinding['strength'] ?? 'example'] ?? 0;
        $diffStrength = $strengthOrder[$diffBinding['strength'] ?? 'example'] ?? 0;

        // Use stronger binding
        if ($diffStrength >= $baseStrength) {
            return $diffBinding;
        }

        return $baseBinding;
    }

    /**
     * Load base StructureDefinition.
     *
     * @throws SnapshotGenerationException
     */
    private function loadBaseDefinition(string $baseDefinitionUrl): array
    {
        // Extract package name from URL (e.g., http://hl7.org/fhir/StructureDefinition/Patient)
        // For now, we'll load from the core FHIR package
        $parts        = explode('/', $baseDefinitionUrl);
        $resourceType = end($parts);

        // Determine FHIR version from URL
        $fhirVersion = $this->getFhirVersionFromUrl($baseDefinitionUrl);
        $packageName = $this->getPackageNameForVersion($fhirVersion);

        try {
            $package        = $this->packageLoader->loadPackage($packageName);
            $baseDefinition = $package->getStructureDefinition($baseDefinitionUrl);

            if ($baseDefinition === null) {
                throw new SnapshotGenerationException("Base StructureDefinition not found: {$baseDefinitionUrl}");
            }

            return $baseDefinition;
        } catch (\Exception $e) {
            throw new SnapshotGenerationException("Failed to load base StructureDefinition {$baseDefinitionUrl}: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Determine FHIR version from URL.
     */
    private function getFhirVersionFromUrl(string $url): string
    {
        // Simple heuristic: check for version indicators in URL
        if (str_contains($url, 'hl7.org/fhir/R4B/')) {
            return '4.3.0';
        }

        if (str_contains($url, 'hl7.org/fhir/R5/')) {
            return '5.0.0';
        }

        // Default to R4
        return '4.0.1';
    }

    /**
     * Get package name for FHIR version.
     */
    private function getPackageNameForVersion(string $version): string
    {
        return match ($version) {
            '4.0.1' => 'hl7.fhir.r4.core',
            '4.3.0' => 'hl7.fhir.r4b.core',
            '5.0.0' => 'hl7.fhir.r5.core',
            default => 'hl7.fhir.r4.core',
        };
    }

    /**
     * Check if cached snapshot is current.
     *
     * @param array<string, mixed> $sd             Current StructureDefinition
     * @param array<string, mixed> $cachedSnapshot Cached StructureDefinition
     */
    private function isSnapshotCurrent(array $sd, array $cachedSnapshot): bool
    {
        // Check if version/date match
        if (($sd['version'] ?? null) !== ($cachedSnapshot['version'] ?? null)) {
            return false;
        }

        if (($sd['date'] ?? null) !== ($cachedSnapshot['date'] ?? null)) {
            return false;
        }

        return true;
    }

    /**
     * Get cache key for StructureDefinition.
     */
    private function getCacheKey(array $sd): string
    {
        $url     = $sd['url'];
        $version = $sd['version'] ?? 'no-version';

        return self::CACHE_KEY_PREFIX . md5($url . '|' . $version);
    }
}
