<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Cache for FHIR metadata to improve performance by avoiding repeated reflection operations.
 *
 * This cache stores metadata extracted from FHIR classes to avoid the overhead
 * of reflection and attribute parsing on every serialization operation.
 *
 * @author Kiro AI Assistant
 */
class FHIRMetadataCache
{
    /** @var array<string, FHIRResourceMetadata|null> */
    private array $resourceTypeCache = [];

    /** @var array<string, FHIRComplexTypeMetadata|null> */
    private array $complexTypeCache = [];

    /** @var array<string, FHIRPrimitiveTypeMetadata|null> */
    private array $primitiveTypeCache = [];

    /** @var array<string, FHIRBackboneElementMetadata|null> */
    private array $backboneElementCache = [];

    /** @var array<string, string|null> */
    private array $fhirTypeCache = [];

    /** @var array<string, string|null> */
    private array $fhirVersionCache = [];

    /** @var array<string, string|null> */
    private array $structureTypeCache = [];

    /**
     * Get cached resource metadata for a class
     */
    public function getResourceMetadata(string $className): ?FHIRResourceMetadata
    {
        return $this->resourceTypeCache[$className] ?? null;
    }

    /**
     * Cache resource metadata for a class
     */
    public function cacheResourceMetadata(string $className, ?FHIRResourceMetadata $metadata): void
    {
        $this->resourceTypeCache[$className] = $metadata;
    }

    /**
     * Get cached complex type metadata for a class
     */
    public function getComplexTypeMetadata(string $className): ?FHIRComplexTypeMetadata
    {
        return $this->complexTypeCache[$className] ?? null;
    }

    /**
     * Cache complex type metadata for a class
     */
    public function cacheComplexTypeMetadata(string $className, ?FHIRComplexTypeMetadata $metadata): void
    {
        $this->complexTypeCache[$className] = $metadata;
    }

    /**
     * Get cached primitive type metadata for a class
     */
    public function getPrimitiveTypeMetadata(string $className): ?FHIRPrimitiveTypeMetadata
    {
        return $this->primitiveTypeCache[$className] ?? null;
    }

    /**
     * Cache primitive type metadata for a class
     */
    public function cachePrimitiveTypeMetadata(string $className, ?FHIRPrimitiveTypeMetadata $metadata): void
    {
        $this->primitiveTypeCache[$className] = $metadata;
    }

    /**
     * Get cached backbone element metadata for a class
     */
    public function getBackboneElementMetadata(string $className): ?FHIRBackboneElementMetadata
    {
        return $this->backboneElementCache[$className] ?? null;
    }

    /**
     * Cache backbone element metadata for a class
     */
    public function cacheBackboneElementMetadata(string $className, ?FHIRBackboneElementMetadata $metadata): void
    {
        $this->backboneElementCache[$className] = $metadata;
    }

    /**
     * Get cached FHIR type for a class
     */
    public function getFHIRTypeMetadata(string $className): ?string
    {
        if (!array_key_exists($className, $this->fhirTypeCache)) {
            return null;
        }

        return $this->fhirTypeCache[$className];
    }

    /**
     * Cache FHIR type for a class
     */
    public function cacheFHIRTypeMetadata(string $className, ?string $fhirType): void
    {
        $this->fhirTypeCache[$className] = $fhirType;
    }

    /**
     * Get cached FHIR version for a class
     */
    public function getFHIRVersionMetadata(string $className): ?string
    {
        if (!array_key_exists($className, $this->fhirVersionCache)) {
            return null;
        }

        return $this->fhirVersionCache[$className];
    }

    /**
     * Cache FHIR version for a class
     */
    public function cacheFHIRVersionMetadata(string $className, ?string $fhirVersion): void
    {
        $this->fhirVersionCache[$className] = $fhirVersion;
    }

    /**
     * Get cached structure type for a class
     */
    public function getStructureTypeMetadata(string $className): ?string
    {
        if (!array_key_exists($className, $this->structureTypeCache)) {
            return null;
        }

        return $this->structureTypeCache[$className];
    }

    /**
     * Cache structure type for a class
     */
    public function cacheStructureTypeMetadata(string $className, ?string $structureType): void
    {
        $this->structureTypeCache[$className] = $structureType;
    }

    /**
     * Invalidate all cached metadata
     */
    public function invalidateCache(): void
    {
        $this->resourceTypeCache    = [];
        $this->complexTypeCache     = [];
        $this->primitiveTypeCache   = [];
        $this->backboneElementCache = [];
        $this->fhirTypeCache        = [];
        $this->fhirVersionCache     = [];
        $this->structureTypeCache   = [];
    }

    /**
     * Invalidate cached metadata for a specific class
     */
    public function invalidateClass(string $className): void
    {
        unset(
            $this->resourceTypeCache[$className],
            $this->complexTypeCache[$className],
            $this->primitiveTypeCache[$className],
            $this->backboneElementCache[$className],
            $this->fhirTypeCache[$className],
            $this->fhirVersionCache[$className],
            $this->structureTypeCache[$className],
        );
    }

    /**
     * Get cache statistics for monitoring and debugging
     *
     * @return array<string, int>
     */
    public function getCacheStats(): array
    {
        return [
            'resource_entries'         => count($this->resourceTypeCache),
            'complex_type_entries'     => count($this->complexTypeCache),
            'primitive_type_entries'   => count($this->primitiveTypeCache),
            'backbone_element_entries' => count($this->backboneElementCache),
            'fhir_type_entries'        => count($this->fhirTypeCache),
            'fhir_version_entries'     => count($this->fhirVersionCache),
            'structure_type_entries'   => count($this->structureTypeCache),
        ];
    }

    /**
     * Check if the cache is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->resourceTypeCache)
               && empty($this->complexTypeCache)
               && empty($this->primitiveTypeCache)
               && empty($this->backboneElementCache)
               && empty($this->fhirTypeCache)
               && empty($this->fhirVersionCache)
               && empty($this->structureTypeCache);
    }
}
