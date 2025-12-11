<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Basic implementation of FHIR type resolver for discriminator map support.
 *
 * This resolver handles type resolution for polymorphic FHIR elements using
 * discriminator maps and other type resolution strategies.
 *
 * @author Kiro AI Assistant
 */
class FHIRTypeResolver implements FHIRTypeResolverInterface
{
    /** @var array<string, string> */
    private array $resourceTypeMapping = [];

    /** @var array<string, string> */
    private array $choiceElementMapping = [];

    /** @var array<string, string> */
    private array $referenceTypeMapping = [];

    public function __construct(array $resourceTypeMapping = [], array $choiceElementMapping = [], array $referenceTypeMapping = [])
    {
        $this->resourceTypeMapping  = $resourceTypeMapping;
        $this->choiceElementMapping = $choiceElementMapping;
        $this->referenceTypeMapping = $referenceTypeMapping;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveType(array $data, array $context = []): ?string
    {
        // Try to resolve as resource type first
        if (isset($data['resourceType'])) {
            return $this->resolveResourceType($data);
        }

        // Try to resolve as choice element
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'value') && $key !== 'value') {
                $type = $this->resolveChoiceElementType('value', $data);
                if ($type !== null) {
                    return $type;
                }
            }
        }

        // Try to resolve as reference
        if (isset($data['reference']) || isset($data['type'])) {
            return $this->resolveReferenceType($data);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getDiscriminatorProperty(): string
    {
        return 'resourceType';
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeMapping(): array
    {
        return $this->resourceTypeMapping;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveResourceType(array $data): ?string
    {
        if (!isset($data['resourceType'])) {
            return null;
        }

        $resourceType = $data['resourceType'];
        if (!is_string($resourceType)) {
            return null;
        }

        // Return the mapped class name if available, otherwise construct a default one
        if (isset($this->resourceTypeMapping[$resourceType])) {
            return $this->resourceTypeMapping[$resourceType];
        }

        // Default mapping: assume classes are named FHIR{ResourceType}
        return 'FHIR' . $resourceType;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveChoiceElementType(string $propertyName, array $data): ?string
    {
        // Look for properties that start with the property name followed by a type
        foreach ($data as $key => $value) {
            if (str_starts_with($key, $propertyName) && $key !== $propertyName) {
                $typeSuffix = substr($key, strlen($propertyName));

                // Check if we have a mapping for this choice element
                $mappingKey = $propertyName . $typeSuffix;
                if (isset($this->choiceElementMapping[$mappingKey])) {
                    return $this->choiceElementMapping[$mappingKey];
                }

                // Default mapping: assume classes are named FHIR{Type}
                return 'FHIR' . $typeSuffix;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveReferenceType(array $referenceData): ?string
    {
        // Try to resolve from explicit type field
        if (isset($referenceData['type'])) {
            $type = $referenceData['type'];
            if (is_string($type) && isset($this->referenceTypeMapping[$type])) {
                return $this->referenceTypeMapping[$type];
            }
        }

        // Try to resolve from reference URL
        if (isset($referenceData['reference'])) {
            $reference = $referenceData['reference'];
            if (is_string($reference)) {
                // Extract resource type from reference like "Patient/123"
                $parts = explode('/', $reference);
                if (count($parts) >= 2) {
                    $resourceType = $parts[0];
                    if (isset($this->resourceTypeMapping[$resourceType])) {
                        return $this->resourceTypeMapping[$resourceType];
                    }

                    return 'FHIR' . $resourceType;
                }
            }
        }

        return null;
    }

    /**
     * Add a resource type mapping
     */
    public function addResourceTypeMapping(string $resourceType, string $className): void
    {
        $this->resourceTypeMapping[$resourceType] = $className;
    }

    /**
     * Add a choice element mapping
     */
    public function addChoiceElementMapping(string $choiceElement, string $className): void
    {
        $this->choiceElementMapping[$choiceElement] = $className;
    }

    /**
     * Add a reference type mapping
     */
    public function addReferenceTypeMapping(string $referenceType, string $className): void
    {
        $this->referenceTypeMapping[$referenceType] = $className;
    }
}
