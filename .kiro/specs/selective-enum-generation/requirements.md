# Requirements Document

## Introduction

This document outlines the requirements for implementing selective enum generation in the PHP FHIR Tools project. Currently, the system generates all enums from all ValueSets in a FHIR package, which creates unnecessary code bloat and impacts performance. The system should be optimized to generate only the enums that are actually required by the StructureDefinitions being processed, based on their binding references.

## Glossary

- **FHIR_Tools**: The PHP FHIR Tools system that generates PHP classes from FHIR definitions
- **Structure_Definition**: FHIR resource that defines the structure and constraints for FHIR resources
- **Value_Set**: FHIR resource that defines a set of codes for use in coded elements
- **Code_System**: FHIR resource that defines a set of codes with meanings
- **Binding**: FHIR mechanism that associates a coded element with a ValueSet
- **Binding_Strength**: The degree to which a ValueSet binding is enforced (required, extensible, preferred, example)
- **Builder_Context**: The central context manager that tracks generated types, definitions, and namespaces
- **Selective_Generation**: The process of generating only required enums based on StructureDefinition references
- **Dependency_Tracking**: The mechanism for tracking which ValueSets are referenced by StructureDefinitions

## Requirements

### Requirement 1

**User Story:** As a developer generating FHIR classes, I want only required enums to be generated, so that the output contains minimal code without unnecessary ValueSet enums.

#### Acceptance Criteria

1. WHEN StructureDefinitions are processed THEN the FHIR_Tools SHALL identify all ValueSet bindings with required strength
2. WHEN ValueSet bindings are found THEN the FHIR_Tools SHALL track the referenced ValueSet URLs for enum generation
3. WHEN enum generation occurs THEN the FHIR_Tools SHALL generate enums only for ValueSets referenced by StructureDefinitions
4. WHEN no StructureDefinitions reference a ValueSet THEN the FHIR_Tools SHALL skip generating an enum for that ValueSet
5. WHEN generation completes THEN the FHIR_Tools SHALL produce only the minimal set of required enums

### Requirement 2

**User Story:** As a developer working with FHIR bindings, I want binding strength to determine enum generation, so that only strongly-bound ValueSets create enums while weakly-bound ones remain as strings.

#### Acceptance Criteria

1. WHEN a binding has required strength THEN the FHIR_Tools SHALL generate an enum for the referenced ValueSet
2. WHEN a binding has extensible strength THEN the FHIR_Tools SHALL use string type without generating an enum
3. WHEN a binding has preferred strength THEN the FHIR_Tools SHALL use string type without generating an enum
4. WHEN a binding has example strength THEN the FHIR_Tools SHALL use string type without generating an enum
5. WHEN no binding strength is specified THEN the FHIR_Tools SHALL treat it as extensible and use string type without generating an enum

### Requirement 3

**User Story:** As a developer maintaining the BuilderContext interface, I want proper interface contracts for dependency tracking, so that the pending enum functionality is properly exposed and testable.

#### Acceptance Criteria

1. WHEN pending enums are tracked THEN the BuilderContextInterface SHALL declare addPendingEnum method
2. WHEN pending enums are retrieved THEN the BuilderContextInterface SHALL declare getPendingEnums method
3. WHEN pending enums are removed THEN the BuilderContextInterface SHALL declare removePendingEnum method
4. WHEN pending types are managed THEN the BuilderContextInterface SHALL declare addPendingType and removePendingType methods
5. WHEN interface contracts are updated THEN the FHIR_Tools SHALL maintain backward compatibility with existing implementations

### Requirement 4

**User Story:** As a developer processing FHIR packages, I want dependency resolution for ValueSets, so that referenced ValueSets are properly loaded and available for enum generation.

#### Acceptance Criteria

1. WHEN a ValueSet URL is referenced THEN the FHIR_Tools SHALL resolve the ValueSet definition from loaded packages
2. WHEN a ValueSet is not found in current packages THEN the FHIR_Tools SHALL attempt to load it from package dependencies
3. WHEN ValueSet resolution fails THEN the FHIR_Tools SHALL log a warning and fall back to string type
4. WHEN versioned ValueSet URLs are encountered THEN the FHIR_Tools SHALL handle version-specific resolution correctly
5. WHEN ValueSet definitions are resolved THEN the FHIR_Tools SHALL cache them for efficient reuse

### Requirement 5

**User Story:** As a developer generating code types, I want automatic code type generation for required enums, so that strongly-typed FHIR code elements are properly supported.

#### Acceptance Criteria

1. WHEN an enum is generated for a ValueSet THEN the FHIR_Tools SHALL generate a corresponding code type class
2. WHEN code type classes are created THEN the FHIR_Tools SHALL extend the appropriate FHIR base code type
3. WHEN code type constructors are generated THEN the FHIR_Tools SHALL accept both enum values and string values
4. WHEN code type classes are documented THEN the FHIR_Tools SHALL include references to the source ValueSet
5. WHEN code types are used in properties THEN the FHIR_Tools SHALL generate proper type hints and documentation

### Requirement 6

**User Story:** As a developer working with nested StructureDefinitions, I want recursive dependency tracking, so that ValueSets referenced in backbone elements and complex types are also included.

#### Acceptance Criteria

1. WHEN backbone elements contain bindings THEN the FHIR_Tools SHALL track their ValueSet dependencies
2. WHEN complex types reference ValueSets THEN the FHIR_Tools SHALL include them in dependency tracking
3. WHEN nested elements have bindings THEN the FHIR_Tools SHALL recursively process all binding references
4. WHEN choice elements (value[x]) have bindings THEN the FHIR_Tools SHALL track ValueSets for all choice types
5. WHEN extension definitions contain bindings THEN the FHIR_Tools SHALL include their ValueSet dependencies