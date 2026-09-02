<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Cache for FHIR metadata to improve performance by avoiding repeated reflection operations.
 *
 * This cache stores metadata extracted from FHIR classes to avoid the overhead
 * of reflection and attribute parsing on every serialization operation.
 *
 * @author Ardenexal
 */
class FHIRMetadataCache
{
    /** Whether the class is a FHIR resource — `#[FhirResource]`, own or inherited. */
    public const string FLAG_RESOURCE = 'resource';

    /** Whether the class is a FHIR complex type. A primitive never is; see FHIRMetadataExtractor. */
    public const string FLAG_COMPLEX_TYPE = 'complex-type';

    /** Whether the class is a FHIR primitive — `#[FHIRPrimitive]`, own or inherited. */
    public const string FLAG_PRIMITIVE_TYPE = 'primitive-type';

    /** Whether the class is a backbone element — `#[FHIRBackboneElement]`, own or inherited. */
    public const string FLAG_BACKBONE_ELEMENT = 'backbone-element';

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

    /**
     * One cached answer per structural question per class.
     *
     * Keyed question-first, because the four questions are **not** mutually exclusive and a single
     * shared slot made them overwrite each other. Every FHIR primitive class carries
     * `#[FHIRPrimitive]` itself and inherits `#[FHIRComplexType]` from `Element`, and every
     * backbone element inherits it too — so `isPrimitiveType()` and `isComplexType()` are both
     * independently true for a primitive. With one slot the first question asked won it and every
     * later question read that one answer, making classification depend on call order: serializing
     * to XML before JSON asked `isComplexType()` first, and `Meta.profile` then serialized as
     * `[{"value":"…"}]` instead of `["…"]` for the rest of the service's life.
     *
     * @var array<string, array<string, bool>> question => class-string => answer
     */
    private array $structureFlagCache = [];

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
     * A class's cached answer to one structural question, or null when it has not been asked yet.
     *
     * `$question` is one of the FLAG_* constants.
     */
    public function getStructureFlag(string $className, string $question): ?bool
    {
        return $this->structureFlagCache[$question][$className] ?? null;
    }

    /**
     * Cache one class's answer to one structural question.
     *
     * Negative answers are cached too. The previous single-slot API stored `null` for "no", which
     * its reader could not tell apart from "not cached", so every negative was recomputed by
     * reflection on every call.
     */
    public function cacheStructureFlag(string $className, string $question, bool $answer): void
    {
        $this->structureFlagCache[$question][$className] = $answer;
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
        $this->structureFlagCache   = [];
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
        );

        // Structure flags are keyed question-first, so one class's answers are spread across every
        // question rather than sitting under a single key.
        foreach (array_keys($this->structureFlagCache) as $question) {
            unset($this->structureFlagCache[$question][$className]);
        }
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
            'structure_flag_entries'   => array_sum(array_map('count', $this->structureFlagCache)),
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
               && empty($this->structureFlagCache);
    }
}
