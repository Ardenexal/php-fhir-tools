<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

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

    private FHIRStructureKindProviderInterface $structureKinds;

    public function __construct(?FHIRMetadataCache $cache = null, ?PropertyMetadataProviderInterface $propertyMetadataProvider = null, ?FHIRStructureKindProviderInterface $structureKinds = null)
    {
        $this->cache                    = $cache                    ?? new FHIRMetadataCache();
        $this->propertyMetadataProvider = $propertyMetadataProvider ?? new PropertyMetadataProvider();
        $this->structureKinds           = $structureKinds           ?? new FHIRStructureKindProvider();
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
        // Delegated rather than reimplemented. This method and
        // FHIRStructureKindProvider::declaredFhirTypeName() answered the same question from two
        // copies of the same attribute walk, and the copies disagreed: the provider scanned argument
        // names and returned null for everything but a resource, while this one -- with no production
        // callers to notice it was right -- read each attribute's own field. One implementation now,
        // and it is the provider's, because it also caches a negative answer and takes a class name
        // as well as an instance.
        return $this->structureKinds->declaredFhirTypeName($object);
    }

    /**
     * {@inheritDoc}
     */
    public function isResource(object $object): bool
    {
        $className = get_class($object);

        // Check cache first
        $cached = $this->cache->getStructureKindFlag($className, 'resource');
        if ($cached !== null) {
            return $cached;
        }

        try {
            // Walk the parent chain so that profile subclasses (e.g. AUBasePatientProfile
            // extends PatientResource) inherit the #[FhirResource] attribute from their base.
            $refl = new \ReflectionClass($object);

            do {
                if (!empty($refl->getAttributes(FhirResource::class))) {
                    $this->cache->cacheStructureKindFlag($className, 'resource', true);

                    return true;
                }

                $refl = $refl->getParentClass();
            } while ($refl !== false);

            $this->cache->cacheStructureKindFlag($className, 'resource', false);

            return false;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureKindFlag($className, 'resource', false);

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
        $cached = $this->cache->getStructureKindFlag($className, 'complex-type');
        if ($cached !== null) {
            return $cached;
        }

        try {
            // Walk the parent class hierarchy: profile subclasses (e.g. AUIHIProfile extends Identifier)
            // carry #[FHIRProfile] rather than #[FHIRComplexType], so we must check parent classes.
            $reflection    = new \ReflectionClass($object);
            $isComplexType = false;
            $r             = $reflection;

            do {
                if (!empty($r->getAttributes(FHIRComplexType::class))) {
                    $isComplexType = true;
                    break;
                }

                $r = $r->getParentClass();
            } while ($r !== false);

            $this->cache->cacheStructureKindFlag($className, 'complex-type', $isComplexType);

            return $isComplexType;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureKindFlag($className, 'complex-type', false);

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
        $cached = $this->cache->getStructureKindFlag($className, 'primitive-type');
        if ($cached !== null) {
            return $cached;
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

            $this->cache->cacheStructureKindFlag($className, 'primitive-type', $isPrimitive);

            return $isPrimitive;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureKindFlag($className, 'primitive-type', false);

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
        $cached = $this->cache->getStructureKindFlag($className, 'backbone-element');
        if ($cached !== null) {
            return $cached;
        }

        try {
            $reflection = new \ReflectionClass($object);
            $attributes = $reflection->getAttributes(FHIRBackboneElement::class);
            $isBackbone = !empty($attributes);

            $this->cache->cacheStructureKindFlag($className, 'backbone-element', $isBackbone);

            return $isBackbone;
        } catch (\ReflectionException) {
            $this->cache->cacheStructureKindFlag($className, 'backbone-element', false);

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
