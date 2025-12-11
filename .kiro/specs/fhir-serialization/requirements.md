# Requirements Document

## Introduction

This document outlines the requirements for implementing comprehensive FHIR serialization and deserialization capabilities for the PHP FHIR Tools project. The system will provide Symfony Serializer-compatible normalizers and denormalizers that can convert between FHIR JSON/XML representations and the generated PHP FHIR model classes, following the official FHIR serialization specifications.

## Glossary

- **FHIR_Serializer**: The Symfony Serializer-based system for converting FHIR objects to/from JSON and XML
- **Normalizer**: Symfony component that converts PHP objects to normalized arrays
- **Denormalizer**: Symfony component that converts normalized arrays back to PHP objects
- **Discriminator_Map**: Symfony attribute that maps type identifiers to specific classes for polymorphic serialization
- **Structure_Definition_Kind**: FHIR classification of structure definitions (resource, complex-type, primitive-type, logical)
- **Backbone_Element**: FHIR element that is defined inline within a resource and has its own properties
- **Resource_Type**: The FHIR resourceType field that identifies the specific type of FHIR resource
- **FHIR_JSON_Spec**: Official FHIR JSON serialization specification at https://hl7.org/fhir/R4B/json.html
- **FHIR_XML_Spec**: Official FHIR XML serialization specification at https://hl7.org/fhir/R4B/xml.html

## Requirements

### Requirement 1

**User Story:** As a developer using FHIR classes, I want to serialize FHIR resources to JSON format, so that I can exchange FHIR data with external systems following the official FHIR JSON specification.

#### Acceptance Criteria

1. WHEN a FHIR resource is serialized to JSON THEN the FHIR_Serializer SHALL produce output conforming to the FHIR_JSON_Spec
2. WHEN a resource contains a resourceType field THEN the FHIR_Serializer SHALL include the resourceType in the JSON output
3. WHEN primitive elements have extensions THEN the FHIR_Serializer SHALL serialize both the value and extension using underscore notation
4. WHEN array elements contain extensions THEN the FHIR_Serializer SHALL handle sparse extension arrays correctly
5. WHEN null or empty values are present THEN the FHIR_Serializer SHALL omit them from JSON output according to FHIR rules

### Requirement 2

**User Story:** As a developer receiving FHIR data, I want to deserialize JSON into FHIR classes, so that I can work with strongly-typed FHIR objects in my application.

#### Acceptance Criteria

1. WHEN valid FHIR JSON is deserialized THEN the FHIR_Serializer SHALL create the correct FHIR class instances
2. WHEN resourceType is specified in JSON THEN the FHIR_Serializer SHALL instantiate the corresponding resource class
3. WHEN primitive extensions are present THEN the FHIR_Serializer SHALL populate both value and extension properties
4. WHEN unknown properties are encountered THEN the FHIR_Serializer SHALL handle them gracefully according to configuration
5. WHEN invalid JSON structure is provided THEN the FHIR_Serializer SHALL throw specific validation exceptions

### Requirement 3

**User Story:** As a developer working with different FHIR structure types, I want specialized normalizers for each kind, so that serialization behavior is optimized for each FHIR structure definition type.

#### Acceptance Criteria

1. WHEN resources are serialized THEN the FHIR_Serializer SHALL use a dedicated resource normalizer
2. WHEN complex types are serialized THEN the FHIR_Serializer SHALL use a dedicated complex type normalizer  
3. WHEN primitive types are serialized THEN the FHIR_Serializer SHALL use a dedicated primitive type normalizer
4. WHEN backbone elements are serialized THEN the FHIR_Serializer SHALL use a dedicated backbone element normalizer
5. WHEN normalizer selection occurs THEN the FHIR_Serializer SHALL automatically choose the correct normalizer based on class type

### Requirement 4

**User Story:** As a developer working with polymorphic FHIR types, I want discriminator map support, so that the serializer can handle FHIR's polymorphic elements correctly.

#### Acceptance Criteria

1. WHEN polymorphic elements are encountered THEN the FHIR_Serializer SHALL use discriminator maps to determine the correct type
2. WHEN choice elements (e.g., value[x]) are serialized THEN the FHIR_Serializer SHALL include the correct type suffix
3. WHEN reference types vary THEN the FHIR_Serializer SHALL handle different reference target types
4. WHEN extension values are polymorphic THEN the FHIR_Serializer SHALL serialize the correct value type
5. WHEN deserializing polymorphic types THEN the FHIR_Serializer SHALL instantiate the correct concrete class

### Requirement 5

**User Story:** As a developer integrating with XML-based FHIR systems, I want XML serialization support, so that I can exchange FHIR data in XML format following the official FHIR XML specification.

#### Acceptance Criteria

1. WHEN FHIR resources are serialized to XML THEN the FHIR_Serializer SHALL produce output conforming to the FHIR_XML_Spec
2. WHEN XML namespaces are required THEN the FHIR_Serializer SHALL include proper FHIR namespace declarations
3. WHEN primitive values have extensions THEN the FHIR_Serializer SHALL serialize them as XML attributes and child elements
4. WHEN XML is deserialized THEN the FHIR_Serializer SHALL correctly parse FHIR XML into PHP objects
5. WHEN XML validation is enabled THEN the FHIR_Serializer SHALL validate against FHIR XML schemas

### Requirement 6

**User Story:** As a developer maintaining FHIR serialization code, I want interface-based design, so that the serialization system is extensible and follows SOLID principles.

#### Acceptance Criteria

1. WHEN normalizers are implemented THEN the FHIR_Serializer SHALL define interfaces for each structure definition kind
2. WHEN new FHIR versions are added THEN the FHIR_Serializer SHALL support interface-based extension
3. WHEN custom serialization logic is needed THEN the FHIR_Serializer SHALL allow interface implementations to be replaced
4. WHEN serialization behavior varies by version THEN the FHIR_Serializer SHALL support version-specific implementations
5. WHEN testing serialization components THEN the FHIR_Serializer SHALL enable easy mocking through interfaces

### Requirement 7

**User Story:** As a developer ensuring data integrity, I want round-trip serialization validation, so that I can verify that serialization and deserialization preserve all FHIR data correctly.

#### Acceptance Criteria

1. WHEN objects are serialized then deserialized THEN the FHIR_Serializer SHALL produce equivalent objects
2. WHEN extensions are present THEN the FHIR_Serializer SHALL preserve all extension data through round-trip
3. WHEN metadata elements exist THEN the FHIR_Serializer SHALL maintain all metadata through serialization cycles
4. WHEN complex nested structures are processed THEN the FHIR_Serializer SHALL preserve all nested relationships
5. WHEN primitive values with extensions are handled THEN the FHIR_Serializer SHALL maintain both value and extension data

### Requirement 8

**User Story:** As a developer working with FHIR class metadata, I want PHP attributes for serialization assistance, so that normalizers can efficiently access the information needed for proper FHIR serialization.

#### Acceptance Criteria

1. WHEN FHIR resources are generated THEN the FHIR_Serializer SHALL add resource-specific attributes containing resourceType information
2. WHEN complex types are generated THEN the FHIR_Serializer SHALL add attributes identifying the FHIR type name
3. WHEN backbone elements are generated THEN the FHIR_Serializer SHALL add attributes linking them to their parent resource
4. WHEN primitive types are generated THEN the FHIR_Serializer SHALL add attributes indicating primitive type behavior
5. WHEN attributes are designed THEN the FHIR_Serializer SHALL create simple, reusable attributes that work across multiple model types

### Requirement 9

**User Story:** As a developer configuring serialization behavior, I want flexible serialization options, so that I can customize the serialization process for different use cases.

#### Acceptance Criteria

1. WHEN serialization format is specified THEN the FHIR_Serializer SHALL support both JSON and XML output formats
2. WHEN validation levels are configured THEN the FHIR_Serializer SHALL provide strict and lenient validation modes
3. WHEN unknown elements are encountered THEN the FHIR_Serializer SHALL handle them according to configured policy
4. WHEN performance optimization is needed THEN the FHIR_Serializer SHALL provide options to skip non-essential validation
5. WHEN debugging is required THEN the FHIR_Serializer SHALL provide detailed serialization context and error information