<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;

/**
 * Extracts FHIR metadata from PHP objects using attributes and reflection.
 *
 * This class provides methods to identify FHIR structure types and extract
 * relevant metadata for serialization purposes.
 *
 * @author Ardenexal
 */
class FHIRMetadataExtractor implements FHIRMetadataExtractorInterface
{
    private FHIRMetadataCache $cache;

    public function __construct(?FHIRMetadataCache $cache = null)
    {
        $this->cache = $cache ?? new FHIRMetadataCache();
    }

    /**
     * {@inheritDoc}
     */
    public function extractResourceType(object $object): ?string
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getResourceMetadata($className);
        if ($cached !== null) {
            return $cached->resourceType;
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FhirResource::class);

            if (empty($attributes)) {
                $this->cache->cacheResourceMetadata($className, null);

                return null;
            }

            $attribute = $attributes[0]->newInstance();
            $metadata  = new FHIRResourceMetadata(
                $attribute->getResourceType(),
                $attribute->fhirVersion,
                $attribute->getProfile(),
            );

            $this->cache->cacheResourceMetadata($className, $metadata);

            return $metadata->resourceType;
        } catch (\ReflectionException) {
            $this->cache->cacheResourceMetadata($className, null);

            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extractFHIRType(object $object): ?string
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getFHIRTypeMetadata($className);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $reflection = new \ReflectionClass($object);

            // Check for resource type
            $resourceAttributes = $reflection->getAttributes(FhirResource::class);
            if (!empty($resourceAttributes)) {
                $attribute = $resourceAttributes[0]->newInstance();
                $type      = $attribute->getResourceType();
                $this->cache->cacheFHIRTypeMetadata($className, $type);

                return $type;
            }

            // Check for complex type
            $complexAttributes = $reflection->getAttributes(FHIRComplexType::class);
            if (!empty($complexAttributes)) {
                $attribute = $complexAttributes[0]->newInstance();
                $type      = $attribute->typeName;
                $this->cache->cacheFHIRTypeMetadata($className, $type);

                return $type;
            }

            // Check for primitive type
            $primitiveAttributes = $reflection->getAttributes(FHIRPrimitive::class);
            if (!empty($primitiveAttributes)) {
                $attribute = $primitiveAttributes[0]->newInstance();
                $type      = $attribute->primitiveType;
                $this->cache->cacheFHIRTypeMetadata($className, $type);

                return $type;
            }

            // Check for backbone element - use element path directly
            $backboneAttributes = $reflection->getAttributes(FHIRBackboneElement::class);
            if (!empty($backboneAttributes)) {
                $attribute = $backboneAttributes[0]->newInstance();
                $type      = $attribute->elementPath;
                $this->cache->cacheFHIRTypeMetadata($className, $type);

                return $type;
            }

            $this->cache->cacheFHIRTypeMetadata($className, null);

            return null;
        } catch (\ReflectionException) {
            $this->cache->cacheFHIRTypeMetadata($className, null);

            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isResource(object $object): bool
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getStructureTypeMetadata($className);
        if ($cached !== null) {
            return $cached === 'resource';
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FhirResource::class);
            $isResource = !empty($attributes);

            $this->cache->cacheStructureTypeMetadata($className, $isResource ? 'resource' : null);

            return $isResource;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureTypeMetadata($className, null);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isComplexType(object $object): bool
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getStructureTypeMetadata($className);
        if ($cached !== null) {
            return $cached === 'complex-type';
        }

        try {
            $reflection    = new \ReflectionClass($object);
            $attributes    = $reflection->getAttributes(FHIRComplexType::class);
            $isComplexType = !empty($attributes);

            $this->cache->cacheStructureTypeMetadata($className, $isComplexType ? 'complex-type' : null);

            return $isComplexType;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureTypeMetadata($className, null);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimitiveType(object $object): bool
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getStructureTypeMetadata($className);
        if ($cached !== null) {
            return $cached === 'primitive-type';
        }

        try {
            // Walk the parent class hierarchy: generated "Type" wrappers (e.g. NarrativeStatusType)
            // extend CodePrimitive which carries the #[FHIRPrimitive] attribute.
            $reflection  = new \ReflectionClass($object);
            $isPrimitive = false;
            $r           = $reflection;

            do {
                if (!empty($r->getAttributes(FHIRPrimitive::class))) {
                    $isPrimitive = true;
                    break;
                }

                $r = $r->getParentClass();
            } while ($r !== false);

            $this->cache->cacheStructureTypeMetadata($className, $isPrimitive ? 'primitive-type' : null);

            return $isPrimitive;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureTypeMetadata($className, null);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isBackboneElement(object $object): bool
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getStructureTypeMetadata($className);
        if ($cached !== null) {
            return $cached === 'backbone-element';
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FHIRBackboneElement::class);
            $isBackbone = !empty($attributes);

            $this->cache->cacheStructureTypeMetadata($className, $isBackbone ? 'backbone-element' : null);

            return $isBackbone;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureTypeMetadata($className, null);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extractFHIRVersion(object $object): ?string
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getFHIRVersionMetadata($className);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $reflection = new \ReflectionClass($object);

            // Check all possible attribute types for FHIR version
            $resourceAttributes = $reflection->getAttributes(FhirResource::class);
            if (!empty($resourceAttributes)) {
                $attribute = $resourceAttributes[0]->newInstance();
                $version   = $attribute->fhirVersion;
                $this->cache->cacheFHIRVersionMetadata($className, $version);

                return $version;
            }

            $complexAttributes = $reflection->getAttributes(FHIRComplexType::class);
            if (!empty($complexAttributes)) {
                $attribute = $complexAttributes[0]->newInstance();
                $version   = $attribute->fhirVersion;
                $this->cache->cacheFHIRVersionMetadata($className, $version);

                return $version;
            }

            $primitiveAttributes = $reflection->getAttributes(FHIRPrimitive::class);
            if (!empty($primitiveAttributes)) {
                $attribute = $primitiveAttributes[0]->newInstance();
                $version   = $attribute->fhirVersion;
                $this->cache->cacheFHIRVersionMetadata($className, $version);

                return $version;
            }

            $backboneAttributes = $reflection->getAttributes(FHIRBackboneElement::class);
            if (!empty($backboneAttributes)) {
                $attribute = $backboneAttributes[0]->newInstance();
                $version   = $attribute->fhirVersion;
                $this->cache->cacheFHIRVersionMetadata($className, $version);

                return $version;
            }

            $this->cache->cacheFHIRVersionMetadata($className, null);

            return null;
        } catch (\ReflectionException) {
            $this->cache->cacheFHIRVersionMetadata($className, null);

            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extractParentResource(object $object): ?string
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getBackboneElementMetadata($className);
        if ($cached !== null) {
            return $cached->parentResource;
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FHIRBackboneElement::class);

            if (empty($attributes)) {
                $this->cache->cacheBackboneElementMetadata($className, null);

                return null;
            }

            $attribute = $attributes[0]->newInstance();
            $metadata  = new FHIRBackboneElementMetadata(
                $attribute->parentResource,
                $attribute->elementPath,
                $attribute->fhirVersion,
            );

            $this->cache->cacheBackboneElementMetadata($className, $metadata);

            return $metadata->parentResource;
        } catch (\ReflectionException) {
            $this->cache->cacheBackboneElementMetadata($className, null);

            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extractElementPath(object $object): ?string
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getBackboneElementMetadata($className);
        if ($cached !== null) {
            return $cached->elementPath;
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FHIRBackboneElement::class);

            if (empty($attributes)) {
                $this->cache->cacheBackboneElementMetadata($className, null);

                return null;
            }

            $attribute = $attributes[0]->newInstance();
            $metadata  = new FHIRBackboneElementMetadata(
                $attribute->parentResource,
                $attribute->elementPath,
                $attribute->fhirVersion,
            );

            $this->cache->cacheBackboneElementMetadata($className, $metadata);

            return $metadata->elementPath;
        } catch (\ReflectionException) {
            $this->cache->cacheBackboneElementMetadata($className, null);

            return null;
        }
    }

    /**
     * Get the cache instance for testing or external access
     */
    public function getCache(): FHIRMetadataCache
    {
        return $this->cache;
    }

    /**
     * Clear all cached metadata
     */
    public function clearCache(): void
    {
        $this->cache->invalidateCache();
    }
}
