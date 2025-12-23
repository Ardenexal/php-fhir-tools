# Implementation Plan: FHIR Models Component

## Overview

This implementation plan creates a new FHIR Models Component that stores pre-generated FHIR model classes for R4, R4B, and R5 versions. The component leverages the existing `fhir:generate` command output and provides a centralized repository of FHIR classes that can be reused across other components.

## Tasks

- [x] 1. Set up component structure and package configuration
  - Create directory structure following the established component pattern
  - Create composer.json with proper package metadata and minimal dependencies
  - Create README.md with comprehensive documentation
  - Set up .gitignore for generated files
  - _Requirements: 6.1, 6.2, 6.3, 6.5_

- [x] 1.1 Write unit tests for package structure validation
  - Test composer.json structure and metadata
  - Test directory structure compliance
  - Test README.md completeness
  - _Requirements: 6.1, 6.2, 6.3_

- [x] 2. Implement utility classes for model management
  - [x] 2.1 Create ModelRegistry class for centralized model access
    - Implement getResourceClass method for resource class resolution
    - Implement getBackboneElementClass method for backbone element resolution
    - Implement getDataTypeClass method for data type class resolution
    - Implement getPrimitiveClass method for primitive type class resolution
    - Implement getEnumClass method for enum class resolution
    - Implement getCodeTypeClass method for code type class resolution
    - Add version validation and error handling
    - _Requirements: 1.2, 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 2.2 Write property test for ModelRegistry namespace resolution
  - **Property 1: Version-specific namespace isolation**
  - **Validates: Requirements 1.2, 1.5, 8.1-8.6**

- [x] 2.3 Create VersionDetector utility class
  - Implement detectVersion method for model instance analysis
  - Implement detectVersionFromClassName method for class name analysis
  - Implement isModelsComponentClass method for component detection
  - Add comprehensive error handling
  - _Requirements: 8.7_

- [x] 2.4 Write property test for VersionDetector functionality
  - **Property 9: Cross-version utility functionality**
  - **Validates: Requirements 8.7**

- [x] 2.5 Create ModelsException class for component-specific errors
  - Implement unsupportedVersion exception method
  - Implement modelNotFound exception method
  - Implement invalidNamespace exception method
  - Follow established exception hierarchy patterns
  - _Requirements: Error handling_

- [x] 2.6 Write unit tests for utility classes
  - Test ModelRegistry methods with various inputs
  - Test VersionDetector with different class structures
  - Test exception handling scenarios
  - _Requirements: 2.1, 2.3, 2.5_

- [x] 3. Modify existing generation command for Models component output
  - [x] 3.1 Extend FHIRModelGeneratorCommand with Models component option
    - Add --models-component command option
    - Implement generateForModelsComponent method
    - Modify namespace generation for Models component structure
    - Update output path logic for new directory structure
    - _Requirements: 2.1, 2.5_

- [x] 3.2 Write property test for generation process consistency
  - **Property 2: Generation process consistency**
  - **Validates: Requirements 2.1, 2.5**

- [x] 3.3 Update output path generation for backbone elements
  - Modify getModelsComponentOutputPath to handle backbone elements
  - Implement generateBackboneElement method for proper grouping
  - Ensure resources stay at top level with backbone elements in subdirectories
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 3.4 Write unit tests for output path generation
  - Test resource output paths
  - Test backbone element output paths
  - Test data type and primitive output paths
  - Test enum and code type output paths
  - _Requirements: 3.1, 3.3_

- [-] 4. Generate FHIR models for all supported versions
  - [x] 4.1 Generate R4 models using modified command
    - Run generation for R4 core resources and data types
    - Verify proper namespace organization
    - Validate generated class structure
    - _Requirements: 2.2, 9.1, 9.2, 9.3_

- [x] 4.2 Write property test for R4 model completeness
  - **Property 1: Version-specific namespace isolation (R4)**
  - **Validates: Requirements 8.1, 8.2, 8.3**

- [x] 4.3 Generate R4B models using modified command
  - Run generation for R4B core resources and data types
  - Verify proper namespace organization
  - Validate generated class structure
  - _Requirements: 2.3, 9.1, 9.2, 9.3_

- [x] 4.4 Write property test for R4B model completeness
  - **Property 1: Version-specific namespace isolation (R4B)**
  - **Validates: Requirements 8.4**

- [x] 4.5 Generate R5 models using modified command
  - Run generation for R5 core resources and data types
  - Verify proper namespace organization
  - Validate generated class structure
  - _Requirements: 2.4, 9.1, 9.2, 9.3_

- [x] 4.6 Write property test for R5 model completeness
  - **Property 1: Version-specific namespace isolation (R5)**
  - **Validates: Requirements 8.5**

- [x] 5. Checkpoint - Ensure all models are generated correctly
  - Ensure all tests pass, ask the user if questions arise.

- [x] 6. Implement code quality compliance
  - [x] 6.1 Add strict types declarations to all generated classes
    - Verify all PHP files contain declare(strict_types=1)
    - Update generation templates if necessary
    - _Requirements: 7.1_

- [x] 6.2 Write property test for strict types compliance
  - **Property 5: Code quality standards compliance (strict types)**
  - **Validates: Requirements 7.1**

- [x] 6.3 Ensure PSR-12 compliance for all generated code
  - Run PHP-CS-Fixer on all generated files
  - Fix any code style violations
  - _Requirements: 7.2_

- [x] 6.4 Write property test for PSR-12 compliance
  - **Property 5: Code quality standards compliance (PSR-12)**
  - **Validates: Requirements 7.2**

- [x] 6.5 Ensure PHPStan compliance for all generated code
  - Run PHPStan analysis on all generated files
  - Fix any static analysis violations
  - _Requirements: 7.3_

- [ ] 6.6 Write property test for PHPStan compliance
  - **Property 5: Code quality standards compliance (PHPStan)**
  - **Validates: Requirements 7.3**

- [ ] 6.7 Add comprehensive PHPDoc to all public classes
  - Verify all public classes have proper PHPDoc annotations
  - Include @author, @see, @description tags
  - _Requirements: 7.5_

- [ ] 6.8 Write property test for documentation completeness
  - **Property 6: Documentation completeness**
  - **Validates: Requirements 7.5**

- [ ] 7. Update other components to use Models component
  - [ ] 7.1 Update Serialization component dependencies
    - Add ardenexal/fhir-models to composer.json
    - Update imports to use Models component classes
    - Update tests to use Models component classes
    - _Requirements: 3.1, 3.2_

- [ ] 7.2 Write integration test for Serialization component
  - **Property 3: Component integration compatibility (Serialization)**
  - **Validates: Requirements 3.3**

- [ ] 7.3 Update CodeGeneration component dependencies
  - Add ardenexal/fhir-models to composer.json
  - Update base class references to use Models component
  - Update generation templates for compatibility
  - _Requirements: 4.1, 4.2, 4.3_

- [ ] 7.4 Write integration test for CodeGeneration component
  - **Property 3: Component integration compatibility (CodeGeneration)**
  - **Validates: Requirements 4.4**

- [ ] 7.5 Update FHIRBundle dependencies
  - Add ardenexal/fhir-models to composer.json
  - Update service configuration for Models component classes
  - Enable autowiring for FHIR model classes
  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 7.6 Write integration test for FHIRBundle
  - **Property 3: Component integration compatibility (FHIRBundle)**
  - **Validates: Requirements 5.4**

- [ ] 8. Comprehensive testing and validation
  - [ ] 8.1 Create property-based test suite
    - Implement all remaining correctness properties
    - Configure tests to run minimum 100 iterations
    - Tag tests with feature and property references
    - _Requirements: All property-based testing requirements_

- [ ] 8.2 Write property test for model inheritance structure
  - **Property 8: Model inheritance structure preservation**
  - **Validates: Requirements 4.5, 9.4**

- [ ] 8.3 Write property test for namespace organization consistency
  - **Property 7: Namespace organization consistency**
  - **Validates: Requirements 8.1-8.5**

- [ ] 8.4 Write property test for test pattern consistency
  - **Property 10: Test pattern consistency**
  - **Validates: Requirements 7.4**

- [ ] 8.5 Create integration test suite
  - Test cross-component functionality
  - Test composer package installation
  - Test autoloading functionality
  - _Requirements: 1.3, 1.4_

- [ ] 8.6 Write unit tests for essential model completeness
  - Test presence of core resources (Patient, Observation, etc.)
  - Test presence of core data types (HumanName, Address, etc.)
  - Test presence of primitive types (FHIRString, FHIRInteger, etc.)
  - Test presence of backbone elements and enums
  - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

- [ ] 9. Final validation and documentation
  - [ ] 9.1 Run complete test suite
    - Execute all unit tests
    - Execute all property-based tests
    - Execute all integration tests
    - Verify 100% test coverage for critical paths
    - _Requirements: All testing requirements_

- [ ] 9.2 Update project documentation
  - Update architecture documentation
  - Update component guides
  - Update migration guide
  - Create Models component guide
  - _Requirements: 6.3_

- [ ] 9.3 Validate package publication readiness
  - Verify composer.json completeness
  - Test package installation process
  - Validate autoloading configuration
  - _Requirements: 6.5_

- [ ] 10. Final checkpoint - Complete validation
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties
- Unit tests validate specific examples and edge cases
- The component follows the established multi-project architecture pattern
- Generated models maintain compatibility with existing serialization and generation infrastructure
