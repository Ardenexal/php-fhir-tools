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

        // Walks the parent chain, so a profile subclass (e.g. AUBasePatientProfile extends
        // PatientResource) answers as the resource it profiles. #[FHIRProfile] is not a structural
        // kind, so the subclass itself declares none and the walk continues to its base.
        $isResource = $this->structureKinds->nearestKindAmong($object, FHIRStructureKind::Resource) !== null;

        $this->cache->cacheStructureKindFlag($className, 'resource', $isResource);

        return $isResource;
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

        // A primitive is never a complex type, and that exclusion is the whole rule here.
        //
        // Both markers are genuinely reachable from a primitive: CanonicalPrimitive carries
        // #[FHIRPrimitive] itself and inherits #[FHIRComplexType] from Element, two classes up. A
        // plain walk for #[FHIRComplexType] therefore answers true for every primitive.
        //
        // It must not. FHIRComplexTypeJsonNormalizer gates supportsNormalization() on this predicate
        // and is registered *ahead* of FHIRPrimitiveTypeJsonNormalizer, so answering true offers it
        // every primitive object in the model, to serialize as `{"value": "..."}` where FHIR
        // requires the bare `"..."`.
        //
        // nearestKindAmong() is the shape of this question: it walks upward like the old hand-rolled
        // loop -- profile subclasses (e.g. AUIHIProfile extends Identifier) declare #[FHIRProfile]
        // rather than a structural marker, so the parent chain is what carries the answer -- while
        // stopping at whichever of the two kinds is reached first. Backbone elements are deliberately
        // still complex types: BackboneElement is not a kind this asks for, so the walk passes
        // through it to Element, and FHIRComplexTypeJsonNormalizer keeps claiming them and handling
        // them internally (search: `$isBackboneElement` there) as it always has.
        $isComplexType = $this->structureKinds->nearestKindAmong(
            $object,
            FHIRStructureKind::ComplexType,
            FHIRStructureKind::PrimitiveType,
        ) === FHIRStructureKind::ComplexType;

        $this->cache->cacheStructureKindFlag($className, 'complex-type', $isComplexType);

        return $isComplexType;
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

        // Walks the parent chain, so a generated "Type" wrapper (e.g. NarrativeStatusType extends
        // CodePrimitive) answers as the primitive it narrows.
        $isPrimitive = $this->structureKinds->nearestKindAmong($object, FHIRStructureKind::PrimitiveType) !== null;

        $this->cache->cacheStructureKindFlag($className, 'primitive-type', $isPrimitive);

        return $isPrimitive;
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

        // Declared only, deliberately: unlike the other three this does not walk the chain, because a
        // profile of a backbone element is a different structural case rather than the same one seen
        // through a subclass. declaredKindOf() is the reading that matches the bare getAttributes()
        // call this replaces.
        $isBackbone = $this->structureKinds->declaredKindOf($object) === FHIRStructureKind::BackboneElement;

        $this->cache->cacheStructureKindFlag($className, 'backbone-element', $isBackbone);

        return $isBackbone;
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
