<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;

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

    private PropertyMetadataProviderInterface $propertyMetadataProvider;

    public function __construct(?FHIRMetadataCache $cache = null, ?PropertyMetadataProviderInterface $propertyMetadataProvider = null)
    {
        $this->cache                    = $cache                    ?? new FHIRMetadataCache();
        $this->propertyMetadataProvider = $propertyMetadataProvider ?? new PropertyMetadataProvider();
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
            // Walk the parent chain so that profile subclasses (e.g. AUBasePatientProfile
            // extends PatientResource) inherit the #[FhirResource] attribute from their base.
            $refl = new \ReflectionClass($object);

            do {
                $attributes = $refl->getAttributes(FhirResource::class);

                if (!empty($attributes)) {
                    $attribute = $attributes[0]->newInstance();
                    $metadata  = new FHIRResourceMetadata(
                        $attribute->getResourceType(),
                        $attribute->fhirVersion,
                        $attribute->getProfile(),
                    );

                    $this->cache->cacheResourceMetadata($className, $metadata);

                    return $metadata->resourceType;
                }

                $refl = $refl->getParentClass();
            } while ($refl !== false);

            $this->cache->cacheResourceMetadata($className, null);

            return null;
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

        $cached = $this->cache->getStructureFlag($className, FHIRMetadataCache::FLAG_RESOURCE);
        if ($cached !== null) {
            return $cached;
        }

        // Walk the parent chain so that profile subclasses (e.g. AUBasePatientProfile
        // extends PatientResource) inherit the #[FhirResource] attribute from their base.
        $isResource = $this->hasAttributeInHierarchy($object, FhirResource::class);

        $this->cache->cacheStructureFlag($className, FHIRMetadataCache::FLAG_RESOURCE, $isResource);

        return $isResource;
    }

    /**
     * {@inheritDoc}
     */
    public function isComplexType(object $object): bool
    {
        $className = get_class($object);

        $cached = $this->cache->getStructureFlag($className, FHIRMetadataCache::FLAG_COMPLEX_TYPE);
        if ($cached !== null) {
            return $cached;
        }

        // A primitive is never a complex type, and that exclusion is the whole rule here.
        //
        // Both attributes are genuinely present on a primitive: CanonicalPrimitive carries
        // #[FHIRPrimitive] itself and inherits #[FHIRComplexType] from Element, two classes up. So
        // the hierarchy walk below finds #[FHIRComplexType] and, left alone, answers true.
        //
        // It must not. FHIRComplexTypeJsonNormalizer gates supportsNormalization() on this
        // predicate and is registered *ahead* of FHIRPrimitiveTypeJsonNormalizer, so answering true
        // hands it every primitive object in the model — which it then serializes as a complex
        // object, `{"value": "…"}` where FHIR requires the bare `"…"`.
        //
        // Backbone elements inherit #[FHIRComplexType] the same way and are deliberately NOT
        // excluded: the complex normalizer has always claimed them and handles them internally
        // (search: `$isBackboneElement` in FHIRComplexTypeJsonNormalizer), so excluding them here
        // would reroute them to FHIRBackboneElementJsonNormalizer and change working behaviour.
        //
        // Walking the parent hierarchy is still required: profile subclasses (e.g. AUIHIProfile
        // extends Identifier) carry #[FHIRProfile] rather than #[FHIRComplexType] of their own.
        $isComplexType = !$this->isPrimitiveType($object)
                         && $this->hasAttributeInHierarchy($object, FHIRComplexType::class);

        $this->cache->cacheStructureFlag($className, FHIRMetadataCache::FLAG_COMPLEX_TYPE, $isComplexType);

        return $isComplexType;
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimitiveType(object $object): bool
    {
        $className = get_class($object);

        $cached = $this->cache->getStructureFlag($className, FHIRMetadataCache::FLAG_PRIMITIVE_TYPE);
        if ($cached !== null) {
            return $cached;
        }

        // Walk the parent class hierarchy: generated "Type" wrappers (e.g. NarrativeStatusType)
        // extend CodePrimitive which carries the #[FHIRPrimitive] attribute.
        $isPrimitive = $this->hasAttributeInHierarchy($object, FHIRPrimitive::class);

        $this->cache->cacheStructureFlag($className, FHIRMetadataCache::FLAG_PRIMITIVE_TYPE, $isPrimitive);

        return $isPrimitive;
    }

    /**
     * {@inheritDoc}
     */
    public function isBackboneElement(object $object): bool
    {
        $className = get_class($object);

        $cached = $this->cache->getStructureFlag($className, FHIRMetadataCache::FLAG_BACKBONE_ELEMENT);
        if ($cached !== null) {
            return $cached;
        }

        // Own attributes only, unlike the three predicates above. A backbone element's parent is
        // BackboneElement, which carries #[FHIRComplexType] and not #[FHIRBackboneElement], so
        // there is nothing to inherit and a walk would only cost reflection.
        $isBackbone = !empty((new \ReflectionClass($object))->getAttributes(FHIRBackboneElement::class));

        $this->cache->cacheStructureFlag($className, FHIRMetadataCache::FLAG_BACKBONE_ELEMENT, $isBackbone);

        return $isBackbone;
    }

    /**
     * Whether the object's class, or any ancestor, carries the given attribute.
     *
     * The four structural predicates each asked this question of a different attribute with their
     * own copy of the walk. One walk, one place to fix.
     *
     * Reflecting an *object* cannot fail the way reflecting a class-string can, so there is
     * nothing to catch here.
     *
     * @param class-string $attribute
     */
    private function hasAttributeInHierarchy(object $object, string $attribute): bool
    {
        $current = new \ReflectionClass($object);

        do {
            if (!empty($current->getAttributes($attribute))) {
                return true;
            }

            $current = $current->getParentClass();
        } while ($current !== false);

        return false;
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
     * {@inheritDoc}
     */
    public function getPropertyMetadataProvider(): PropertyMetadataProviderInterface
    {
        return $this->propertyMetadataProvider;
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
