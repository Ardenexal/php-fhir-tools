# Implementation Plan

- [x] 1. Update BuilderContextInterface to expose pending enum methods





  - Add missing method declarations to BuilderContextInterface for pending enum management
  - Ensure interface matches the existing BuilderContext implementation
  - Add proper PHPDoc annotations for all new interface methods
  - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [ ]* 1.1 Write property test for interface contract compliance
  - **Property 1: Interface method availability**
  - **Validates: Requirements 3.1, 3.2, 3.3, 3.4**

- [x] 2. Enhance FHIRModelGenerator to track ValueSet dependencies





  - [x] 2.1 Update addElementAsProperty method to detect bindings and add pending enums


    - Modify the existing method to check for binding elements with required strength
    - Add logic to call addPendingEnum and addPendingType on BuilderContext
    - Handle versioned ValueSet URLs by stripping version information
    - _Requirements: 1.1, 1.2, 2.1_

- [ ]* 2.2 Write property test for binding detection
  - **Property 2: Binding strength determines generation**
  - **Validates: Requirements 2.1, 2.2, 2.3, 2.4**

- [ ]* 2.3 Write property test for default binding strength
  - **Property 3: Default binding strength handling**
  - **Validates: Requirements 2.5**

- [x] 2.4 Add helper method shouldGenerateEnumForBinding


  - Create method to determine if binding strength warrants enum generation
  - Only return true for "required" binding strength
  - Handle missing binding strength as extensible (no enum generation)
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

- [ ]* 2.5 Write property test for selective generation
  - **Property 1: Selective enum generation**
  - **Validates: Requirements 1.1, 1.2, 1.3, 1.4, 1.5**

- [x] 3. Update FHIRModelGeneratorCommand to remove bulk ValueSet processing





  - [x] 3.1 Remove the processValueSets method that processes all ValueSets upfront


    - Delete the method that adds all ValueSets as pending enums
    - Remove the logic that filters ValueSets by size and system type
    - _Requirements: 1.3, 1.4, 1.5_

- [x] 3.2 Update generateClassesForPackage to skip initial ValueSet processing


  - Remove the call to processValueSets from the generation workflow
  - Ensure buildElementClasses is called before buildEnumsForValuesSets
  - _Requirements: 1.3, 1.4, 1.5_



- [-] 4. Enhance ValueSet resolution and error handling



  - [x] 4.1 Improve ValueSet URL resolution in addElementAsProperty


    - Add logic to resolve ValueSet definitions from BuilderContext
    - Handle versioned URLs by extracting base URL for resolution
    - Add fallback to string type when ValueSet cannot be resolved
    - _Requirements: 4.1, 4.2, 4.3, 4.4_

- [ ]* 4.2 Write property test for ValueSet resolution
  - **Property 4: ValueSet resolution with fallback**
  - **Validates: Requirements 4.1, 4.2, 4.3**

- [ ]* 4.3 Write property test for versioned ValueSet handling
  - **Property 5: Versioned ValueSet handling**
  - **Validates: Requirements 4.4**

- [ ]* 4.4 Write property test for ValueSet caching
  - **Property 6: ValueSet caching consistency**
  - **Validates: Requirements 4.5**

- [x] 4.5 Add comprehensive error logging for ValueSet resolution



  - Log binding discoveries with URL and strength information
  - Log successful and failed ValueSet resolutions with details
  - Add debug logging for dependency tracking when verbose mode enabled
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ]* 4.6 Write property test for logging behavior
  - **Property 12: Comprehensive logging**
  - **Validates: Requirements 7.4, 8.1, 8.2, 8.3, 8.4, 8.5**

- [-] 5. Ensure proper enum and code type generation


  - [x] 5.1 Verify buildEnumsForValuesSets uses generateModelCodeType correctly



    - Ensure the existing method calls generateModelCodeType for each generated enum
    - Verify code types are added to BuilderContext and element namespace
    - Confirm proper inheritance and constructor parameter handling
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [ ]* 5.2 Write property test for enum and code type pairing
  - **Property 7: Enum and code type pairing**
  - **Validates: Requirements 5.1, 5.2, 5.3**

- [ ]* 5.3 Write property test for code type documentation
  - **Property 8: Code type documentation completeness**
  - **Validates: Requirements 5.4, 5.5**

- [x] 6. Add support for recursive dependency tracking




  - [x] 6.1 Enhance createForElement to handle nested bindings

    - Ensure bindings in backbone elements are processed recursively
    - Handle bindings in complex types and choice elements
    - Process extension definitions that contain bindings
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ]* 6.2 Write property test for recursive dependency tracking
  - **Property 9: Recursive dependency tracking**
  - **Validates: Requirements 6.1, 6.2, 6.3, 6.4, 6.5**

- [x] 7. Checkpoint - Ensure all tests pass






  - Ensure all tests pass, ask the user if questions arise.

- [x] 8. Add comprehensive unit tests for edge cases
  - [x] 8.1 Test empty StructureDefinitions with no bindings
    - Verify no enums are generated when no bindings exist
    - Ensure system handles empty element arrays gracefully
    - _Requirements: 1.4, 1.5_

- [x] 8.2 Test malformed binding definitions
  - Handle bindings with missing valueSet URLs
  - Process bindings with invalid strength values
  - Verify graceful handling of malformed binding structures
  - _Requirements: 4.3, 8.3_

- [x] 8.3 Test circular dependency scenarios
  - Handle ValueSets that reference each other
  - Prevent infinite loops in dependency resolution
  - Ensure proper error reporting for circular references
  - _Requirements: 4.1, 4.2, 4.3_

- [x] 8.4 Write unit tests for BuilderContext interface compliance
  - Test all pending enum management methods
  - Verify proper state management and consistency
  - Test error conditions and edge cases
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 9. Final Checkpoint - Make sure all tests are passing
  - Ensure all tests pass, ask the user if questions arise.