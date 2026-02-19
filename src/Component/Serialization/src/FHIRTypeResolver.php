<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Comprehensive FHIR discriminator map resolver for polymorphic type resolution.
 *
 * This resolver handles type resolution for polymorphic FHIR elements using
 * discriminator maps and other type resolution strategies including:
 * - Resource type resolution based on resourceType field
 * - Choice element resolution (value[x] patterns)
 * - Reference type resolution for polymorphic references
 * - Extension value type resolution
 *
 * @author Ardenexal
 */
class FHIRTypeResolver implements FHIRTypeResolverInterface
{
    /** @var array<string, string> */
    private array $resourceTypeMapping = [];

    /** @var array<string, string> */
    private array $choiceElementMapping = [];

    /** @var array<string, string> */
    private array $referenceTypeMapping = [];

    /** @var array<string, string> */
    private array $extensionValueMapping = [];

    /** @var array<string, string> */
    private array $complexTypeMapping = [];

    /**
     * @param array<string, string> $resourceTypeMapping
     * @param array<string, string> $choiceElementMapping
     * @param array<string, string> $referenceTypeMapping
     * @param array<string, string> $extensionValueMapping
     * @param array<string, string> $complexTypeMapping
     */
    public function __construct(
        array $resourceTypeMapping = [],
        array $choiceElementMapping = [],
        array $referenceTypeMapping = [],
        array $extensionValueMapping = [],
        array $complexTypeMapping = []
    ) {
        $this->resourceTypeMapping   = $resourceTypeMapping;
        $this->choiceElementMapping  = $choiceElementMapping;
        $this->referenceTypeMapping  = $referenceTypeMapping;
        $this->extensionValueMapping = $extensionValueMapping;
        $this->complexTypeMapping    = $complexTypeMapping;
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

        // Try to resolve as extension with polymorphic value
        if (isset($data['url']) && $this->hasExtensionValue($data)) {
            $type = $this->resolveExtensionValueType($data);
            if ($type !== null) {
                return $type;
            }
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

        // Try to resolve as complex type using context or data structure
        return $this->resolveComplexType($data, $context);
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

        // Return the mapped class name if available
        if (isset($this->resourceTypeMapping[$resourceType])) {
            return $this->resourceTypeMapping[$resourceType];
        }

        // Convention-based fallback: try Models namespace for each supported FHIR version.
        // Whichever version's classes are installed will be found automatically.
        foreach (['R4', 'R4B', 'R5'] as $version) {
            $candidate = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource\\{$resourceType}Resource";
            if (class_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
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
            if (is_string($type)) {
                if (isset($this->referenceTypeMapping[$type])) {
                    return $this->referenceTypeMapping[$type];
                }

                // Default mapping for explicit type field
                return 'FHIR' . $type;
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

    /**
     * Add an extension value type mapping
     */
    public function addExtensionValueMapping(string $valueType, string $className): void
    {
        $this->extensionValueMapping[$valueType] = $className;
    }

    /**
     * Add a complex type mapping
     */
    public function addComplexTypeMapping(string $complexType, string $className): void
    {
        $this->complexTypeMapping[$complexType] = $className;
    }

    /**
     * Get all resource type mappings
     *
     * @return array<string, string>
     */
    public function getResourceTypeMappings(): array
    {
        return $this->resourceTypeMapping;
    }

    /**
     * Get all choice element mappings
     *
     * @return array<string, string>
     */
    public function getChoiceElementMappings(): array
    {
        return $this->choiceElementMapping;
    }

    /**
     * Get all reference type mappings
     *
     * @return array<string, string>
     */
    public function getReferenceTypeMappings(): array
    {
        return $this->referenceTypeMapping;
    }

    /**
     * Get all extension value mappings
     *
     * @return array<string, string>
     */
    public function getExtensionValueMappings(): array
    {
        return $this->extensionValueMapping;
    }

    /**
     * Get all complex type mappings
     *
     * @return array<string, string>
     */
    public function getComplexTypeMappings(): array
    {
        return $this->complexTypeMapping;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveComplexType(array $data, array $context = []): ?string
    {
        // If context provides a hint about the expected type, use it
        if (isset($context['expected_type'])) {
            return $context['expected_type'];
        }

        // Try to infer complex type from data structure patterns
        if (isset($context['property_name'])) {
            $propertyName = $context['property_name'];

            // Check if we have a mapping for this property to a complex type
            if (isset($this->complexTypeMapping[$propertyName])) {
                return $this->complexTypeMapping[$propertyName];
            }
        }

        // Try to infer from common FHIR complex type patterns
        if (isset($data['system']) && isset($data['code'])) {
            return $this->complexTypeMapping['Coding'] ?? 'FHIRCoding';
        }

        if (isset($data['family']) || isset($data['given'])) {
            return $this->complexTypeMapping['HumanName'] ?? 'FHIRHumanName';
        }

        if (isset($data['line']) || isset($data['city']) || isset($data['postalCode'])) {
            return $this->complexTypeMapping['Address'] ?? 'FHIRAddress';
        }

        if (isset($data['start']) || isset($data['end'])) {
            return $this->complexTypeMapping['Period'] ?? 'FHIRPeriod';
        }

        if (isset($data['value']) && isset($data['unit'])) {
            return $this->complexTypeMapping['Quantity'] ?? 'FHIRQuantity';
        }

        return null;
    }

    /**
     * Resolves the type for FHIR extension values (polymorphic extensions).
     *
     * @param array<string, mixed> $extensionData The extension data containing value[x]
     *
     * @return string|null The resolved extension value type, or null if not found
     */
    public function resolveExtensionValueType(array $extensionData): ?string
    {
        // Look for value[x] patterns in extension data
        foreach ($extensionData as $key => $value) {
            if (str_starts_with($key, 'value') && $key !== 'value') {
                $typeSuffix = substr($key, 5); // Remove 'value' prefix

                // Check if we have a mapping for this extension value type
                if (isset($this->extensionValueMapping[$typeSuffix])) {
                    return $this->extensionValueMapping[$typeSuffix];
                }

                // Default mapping: assume classes are named FHIR{Type}
                return 'FHIR' . $typeSuffix;
            }
        }

        return null;
    }

    /**
     * Checks if the given data contains an extension value field.
     *
     * @param array<string, mixed> $data The data to check
     *
     * @return bool True if extension value field is present
     */
    private function hasExtensionValue(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'value') && $key !== 'value') {
                return true;
            }
        }

        return false;
    }
}
