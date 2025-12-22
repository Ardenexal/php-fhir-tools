# Design Document

## Overview

This design document outlines the architecture for implementing comprehensive FHIR serialization and deserialization capabilities using Symfony's Serializer component. The system will provide specialized normalizers and denormalizers for different FHIR structure definition kinds (resources, complex types, primitive types, and backbone elements), with support for both JSON and XML formats following the official FHIR specifications.

The design emphasizes type safety, performance, and extensibility while maintaining compatibility with Symfony 6.4+ serializer patterns. The system will use PHP attributes for metadata, discriminator maps for polymorphic types, and interfaces for clean separation of concerns.

## Architecture

The FHIR serialization system will be organized into several layers:

```
┌─────────────────────────────────────────────────────────────┐
│                    Symfony Serializer                      │
├─────────────────────────────────────────────────────────────┤
│  SerializerInterface │ NormalizerInterface │ Context        │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                FHIR Normalizer Layer                        │
├─────────────────────────────────────────────────────────────┤
│ ResourceNormalizer │ ComplexTypeNormalizer │ PrimitiveNorm. │
│ BackboneElementNormalizer │ DiscriminatorResolver          │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                Interface Layer                              │
├─────────────────────────────────────────────────────────────┤
│ FHIRNormalizerInterface │ FHIRTypeResolverInterface        │
│ FHIRMetadataExtractorInterface                             │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                Attribute & Metadata Layer                   │
├─────────────────────────────────────────────────────────────┤
│ FHIRResource │ FHIRComplexType │ FHIRPrimitive │ FHIRBackbone│
│ DiscriminatorMap │ MetadataExtractor                       │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                Generated FHIR Classes                       │
├─────────────────────────────────────────────────────────────┤
│ Resources │ ComplexTypes │ PrimitiveTypes │ BackboneElements│
└─────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Core Interfaces

**FHIRNormalizerInterface**
```php
interface FHIRNormalizerInterface extends NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool;
    public function getSupportedTypes(?string $format): array;
}
```

**FHIRTypeResolverInterface**
```php
interface FHIRTypeResolverInterface
{
    public function resolveType(array $data, array $context = []): ?string;
    public function getDiscriminatorProperty(): string;
    public function getTypeMapping(): array;
}
```

**FHIRMetadataExtractorInterface**
```php
interface FHIRMetadataExtractorInterface
{
    public function extractResourceType(object $object): ?string;
    public function extractFHIRType(object $object): ?string;
    public function isResource(object $object): bool;
    public function isComplexType(object $object): bool;
    public function isPrimitiveType(object $object): bool;
    public function isBackboneElement(object $object): bool;
}
```

### 2. PHP Attributes for Metadata

**FHIRResource Attribute**
```php
#[Attribute(Attribute::TARGET_CLASS)]
class FHIRResource
{
    public function __construct(
        public readonly string $resourceType,
        public readonly string $fhirVersion = 'R4B',
        public readonly ?string $profile = null
    ) {}
}
```

**FHIRComplexType Attribute**
```php
#[Attribute(Attribute::TARGET_CLASS)]
class FHIRComplexType
{
    public function __construct(
        public readonly string $typeName,
        public readonly string $fhirVersion = 'R4B'
    ) {}
}
```

**FHIRPrimitive Attribute**
```php
#[Attribute(Attribute::TARGET_CLASS)]
class FHIRPrimitive
{
    public function __construct(
        public readonly string $primitiveType,
        public readonly string $fhirVersion = 'R4B'
    ) {}
    
    public function supportsExtensions(): bool
    {
        return true; // All FHIR primitives support extensions
    }
}
```

**FHIRBackboneElement Attribute**
```php
#[Attribute(Attribute::TARGET_CLASS)]
class FHIRBackboneElement
{
    public function __construct(
        public readonly string $parentResource,
        public readonly string $elementPath,
        public readonly string $fhirVersion = 'R4B'
    ) {}
}
```

### 3. Specialized Normalizers

**FHIRResourceNormalizer**
- Handles FHIR resources with resourceType field
- Manages resource-level extensions and metadata
- Supports discriminator maps for polymorphic resources
- Implements FHIR JSON resource serialization rules

**FHIRComplexTypeNormalizer**
- Handles complex FHIR data types (Address, HumanName, etc.)
- Manages nested object serialization
- Supports choice elements (value[x] patterns)
- Handles complex type extensions

**FHIRPrimitiveTypeNormalizer**
- Handles FHIR primitive types with extensions
- Implements underscore notation for primitive extensions in JSON
- Manages primitive value validation
- Supports XML attribute serialization for primitives

**FHIRBackboneElementNormalizer**
- Handles inline backbone elements within resources
- Manages backbone element extensions and modifierExtensions
- Supports nested backbone element structures
- Maintains parent-child relationships

### 4. Discriminator Map Support

**DiscriminatorMapResolver**
```php
class DiscriminatorMapResolver implements FHIRTypeResolverInterface
{
    public function resolveResourceType(array $data): ?string;
    public function resolveChoiceElementType(string $propertyName, array $data): ?string;
    public function resolveReferenceType(array $referenceData): ?string;
}
```

**Choice Element Handling**
- Automatic detection of value[x] patterns
- Type-specific serialization (valueString, valueInteger, etc.)
- Proper deserialization to correct PHP types
- Support for all FHIR choice element types

## Data Models

### Serialization Context

```php
class FHIRSerializationContext
{
    public function __construct(
        public readonly string $format = 'json', // 'json' or 'xml'
        public readonly bool $strictValidation = true,
        public readonly bool $includeExtensions = true,
        public readonly bool $includeMetadata = true,
        public readonly array $unknownElementPolicy = ['ignore'], // 'ignore', 'error', 'preserve'
        public readonly bool $validateReferences = false,
        public readonly array $customNormalizers = []
    ) {}
}
```

### Metadata Cache

```php
class FHIRMetadataCache
{
    private array $resourceTypeCache = [];
    private array $complexTypeCache = [];
    private array $primitiveTypeCache = [];
    private array $backboneElementCache = [];
    
    public function getResourceMetadata(string $className): ?FHIRResourceMetadata;
    public function getComplexTypeMetadata(string $className): ?FHIRComplexTypeMetadata;
    public function invalidateCache(): void;
}
```

### Generated Class Modifications

Generated FHIR classes will be enhanced with attributes:

```php
#[FHIRResource(resourceType: 'Patient')]
class FHIRPatient
{
    public function __construct(
        public ?string $resourceType = 'Patient',
        // ... other properties
    ) {}
}

#[FHIRComplexType(typeName: 'HumanName')]
class FHIRHumanName
{
    // ... properties
}

#[FHIRPrimitive(primitiveType: 'string', hasExtensions: true)]
class FHIRString
{
    // ... properties
}

#[FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.contact')]
class FHIRPatientContact
{
    // ... properties
}
```

## Error Handling

### Exception Hierarchy

```php
abstract class FHIRSerializationException extends FHIRToolsException {}

class FHIRNormalizationException extends FHIRSerializationException {}
class FHIRDenormalizationException extends FHIRSerializationException {}
class FHIRTypeResolutionException extends FHIRSerializationException {}
class FHIRValidationException extends FHIRSerializationException {}
```

### Error Context

All serialization exceptions will include:
- Element path where error occurred
- Expected vs actual data types
- Validation rule that failed
- Suggestions for resolution

## Testing Strategy

The testing approach will combine unit testing and property-based testing using PHPUnit 12+ with Eris for property-based testing:

### Unit Testing
- **Normalizer Tests**: Test each normalizer with specific FHIR examples
- **Attribute Tests**: Verify attribute metadata extraction works correctly
- **Integration Tests**: Test complete serialization workflows with real FHIR data
- **Edge Case Tests**: Test boundary conditions and error scenarios

### Property-Based Testing
- **Round-trip Properties**: Verify serialization/deserialization preserves data integrity using **PHPUnit with Eris**
- **Format Compliance**: Test that output conforms to FHIR JSON/XML specifications
- **Type Safety**: Verify correct type resolution across all FHIR types
- **Extension Handling**: Test extension serialization across different element types

### Test Configuration
- **Minimum 100 iterations** for each property-based test
- **Test tagging** with explicit references to design document properties using format: `**Feature: fhir-serialization, Property {number}: {property_text}**`
- **Official FHIR test data** from https://github.com/FHIR/fhir-test-cases for validation
- **Custom generators** for FHIR-specific data structures

### Testing Framework Requirements
- Use **PHPUnit 12+** as the primary testing framework
- Integrate **Eris** for property-based testing capabilities
- Use official FHIR test cases for comprehensive validation
- Create custom generators for different FHIR structure types
- Test against both JSON and XML formats
- Validate against official FHIR examples and edge cases

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

Based on the prework analysis, the following correctness properties will ensure the FHIR serialization system behaves correctly:

### JSON Serialization Properties

**Property 1: FHIR JSON specification compliance**
*For any* FHIR resource, serializing to JSON should produce output that conforms to the official FHIR JSON specification rules
**Validates: Requirements 1.1**

**Property 2: ResourceType inclusion**
*For any* FHIR resource with a resourceType field, the JSON output should include the resourceType property
**Validates: Requirements 1.2**

**Property 3: Primitive extension underscore notation**
*For any* primitive element with extensions, JSON serialization should create both the value field and the underscore-prefixed extension field
**Validates: Requirements 1.3**

**Property 4: Sparse extension array handling**
*For any* array containing elements with extensions, the serializer should correctly handle sparse extension arrays according to FHIR rules
**Validates: Requirements 1.4**

**Property 5: Null value omission**
*For any* FHIR object with null or empty values, those values should be omitted from JSON output according to FHIR specification
**Validates: Requirements 1.5**

### JSON Deserialization Properties

**Property 6: Correct class instantiation**
*For any* valid FHIR JSON input, deserialization should create instances of the correct FHIR class types
**Validates: Requirements 2.1**

**Property 7: ResourceType-based class resolution**
*For any* JSON with a resourceType field, the deserializer should instantiate the corresponding resource class
**Validates: Requirements 2.2**

**Property 8: Primitive extension deserialization**
*For any* JSON with primitive extension underscore notation, both the value and extension properties should be populated in the resulting object
**Validates: Requirements 2.3**

**Property 9: Configurable unknown property handling**
*For any* JSON containing unknown properties, the deserializer should handle them according to the configured policy (ignore, error, or preserve)
**Validates: Requirements 2.4**

**Property 10: Invalid JSON exception handling**
*For any* invalid JSON structure, the deserializer should throw specific validation exceptions with meaningful error messages
**Validates: Requirements 2.5**

### Normalizer Selection Properties

**Property 11: Resource normalizer selection**
*For any* FHIR resource object, the serializer should automatically select and use the dedicated resource normalizer
**Validates: Requirements 3.1**

**Property 12: Complex type normalizer selection**
*For any* FHIR complex type object, the serializer should automatically select and use the dedicated complex type normalizer
**Validates: Requirements 3.2**

**Property 13: Primitive type normalizer selection**
*For any* FHIR primitive type object, the serializer should automatically select and use the dedicated primitive type normalizer
**Validates: Requirements 3.3**

**Property 14: Backbone element normalizer selection**
*For any* FHIR backbone element object, the serializer should automatically select and use the dedicated backbone element normalizer
**Validates: Requirements 3.4**

**Property 15: Automatic normalizer selection**
*For any* FHIR object, the serializer should automatically choose the correct normalizer based on the object's class type and attributes
**Validates: Requirements 3.5**

### Polymorphic Type Properties

**Property 16: Discriminator map type resolution**
*For any* polymorphic FHIR element, the serializer should use discriminator maps to correctly determine and serialize the specific type
**Validates: Requirements 4.1**

**Property 17: Choice element type suffix**
*For any* FHIR choice element (value[x] pattern), the serializer should include the correct type suffix in the serialized output
**Validates: Requirements 4.2**

**Property 18: Polymorphic reference handling**
*For any* FHIR reference with varying target types, the serializer should correctly handle different reference target types
**Validates: Requirements 4.3**

**Property 19: Polymorphic extension value serialization**
*For any* extension with polymorphic values, the serializer should serialize the correct value type based on the actual value
**Validates: Requirements 4.4**

**Property 20: Polymorphic type deserialization**
*For any* serialized polymorphic type, deserialization should instantiate the correct concrete class based on type information
**Validates: Requirements 4.5**

### XML Serialization Properties

**Property 21: FHIR XML specification compliance**
*For any* FHIR resource, serializing to XML should produce output that conforms to the official FHIR XML specification
**Validates: Requirements 5.1**

**Property 22: XML namespace inclusion**
*For any* FHIR XML output, proper FHIR namespace declarations should be included according to the specification
**Validates: Requirements 5.2**

**Property 23: XML primitive extension serialization**
*For any* primitive value with extensions, XML serialization should correctly serialize them as XML attributes and child elements
**Validates: Requirements 5.3**

**Property 24: XML deserialization accuracy**
*For any* valid FHIR XML input, deserialization should correctly parse the XML into appropriate PHP FHIR objects
**Validates: Requirements 5.4**

**Property 25: XML schema validation**
*For any* FHIR XML when validation is enabled, the output should validate against official FHIR XML schemas
**Validates: Requirements 5.5**

### Interface Design Properties

**Property 26: Version-specific serialization support**
*For any* FHIR version with specific serialization requirements, the system should support version-specific implementations through interfaces
**Validates: Requirements 6.4**

### Round-trip Properties

**Property 27: Object equivalence preservation**
*For any* FHIR object, serializing then deserializing should produce an object equivalent to the original
**Validates: Requirements 7.1**

**Property 28: Extension data preservation**
*For any* FHIR object with extensions, all extension data should be preserved through complete serialization round-trips
**Validates: Requirements 7.2**

**Property 29: Metadata preservation**
*For any* FHIR object with metadata elements, all metadata should be maintained through serialization cycles
**Validates: Requirements 7.3**

**Property 30: Nested structure preservation**
*For any* complex nested FHIR structure, all nested relationships should be preserved through serialization and deserialization
**Validates: Requirements 7.4**

**Property 31: Primitive extension round-trip**
*For any* primitive value with extensions, both the primitive value and extension data should be maintained through round-trip serialization
**Validates: Requirements 7.5**

### Attribute Generation Properties

**Property 32: Resource attribute generation**
*For any* generated FHIR resource class, appropriate resource-specific attributes containing resourceType information should be present
**Validates: Requirements 8.1**

**Property 33: Complex type attribute generation**
*For any* generated FHIR complex type class, attributes identifying the FHIR type name should be present
**Validates: Requirements 8.2**

**Property 34: Backbone element attribute generation**
*For any* generated FHIR backbone element class, attributes linking it to its parent resource should be present
**Validates: Requirements 8.3**

**Property 35: Primitive type attribute generation**
*For any* generated FHIR primitive type class, attributes indicating primitive type behavior should be present
**Validates: Requirements 8.4**

**Property 36: Attribute reusability**
*For any* generated FHIR class attribute, it should be simple and reusable across multiple model types of the same kind
**Validates: Requirements 8.5**

### Configuration Properties

**Property 37: Format support**
*For any* specified serialization format (JSON or XML), the serializer should correctly produce output in that format
**Validates: Requirements 9.1**

**Property 38: Validation mode support**
*For any* configured validation level (strict or lenient), the serializer should behave according to the specified validation mode
**Validates: Requirements 9.2**

**Property 39: Unknown element policy enforcement**
*For any* configured unknown element policy, the serializer should handle unknown elements according to that policy
**Validates: Requirements 9.3**

**Property 40: Performance optimization options**
*For any* performance optimization configuration, the serializer should provide options to skip non-essential validation when requested
**Validates: Requirements 9.4**

**Property 41: Debug information availability**
*For any* serialization operation when debugging is enabled, detailed serialization context and error information should be available
**Validates: Requirements 9.5**