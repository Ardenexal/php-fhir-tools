subm# Implementation Plan

- [x] 1. Create PHP attributes for FHIR metadata





  - Create FHIRResource attribute for resource classes with resourceType information
  - Create FHIRComplexType attribute for complex type classes with type name
  - Create FHIRPrimitive attribute for primitive types with extension support info
  - Create FHIRBackboneElement attribute for backbone elements with parent resource info
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 1.1 Write property test for resource attribute generation


  - **Property 32: Resource attribute generation**
  - **Validates: Requirements 8.1**

- [x] 1.2 Write property test for complex type attribute generation


  - **Property 33: Complex type attribute generation**
  - **Validates: Requirements 8.2**

- [x] 1.3 Write property test for backbone element attribute generation


  - **Property 34: Backbone element attribute generation**
  - **Validates: Requirements 8.3**

- [x] 1.4 Write property test for primitive type attribute generation


  - **Property 35: Primitive type attribute generation**
  - **Validates: Requirements 8.4**

- [x] 1.5 Write property test for attribute reusability


  - **Property 36: Attribute reusability**
  - **Validates: Requirements 8.5**

- [ ] 2. Implement core interfaces for FHIR serialization
  - Create FHIRNormalizerInterface extending Symfony normalizer interfaces
  - Create FHIRTypeResolverInterface for discriminator map support
  - Create FHIRMetadataExtractorInterface for attribute-based metadata extraction
  - Define interface contracts for extensibility and testing
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ] 2.1 Write property test for version-specific serialization support
  - **Property 26: Version-specific serialization support**
  - **Validates: Requirements 6.4**

- [ ] 3. Create metadata extraction and caching system
  - Implement FHIRMetadataExtractor to read PHP attributes from classes
  - Create FHIRMetadataCache for performance optimization
  - Add methods to identify resource, complex type, primitive, and backbone element classes
  - Implement cache invalidation and management
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ] 4. Implement FHIRResourceNormalizer
  - Create normalizer for FHIR resource classes with resourceType handling
  - Implement JSON serialization following FHIR JSON specification
  - Add support for resource-level extensions and metadata
  - Implement discriminator map support for polymorphic resources
  - Handle resourceType field inclusion and validation
  - _Requirements: 1.1, 1.2, 2.1, 2.2, 3.1, 4.1_

- [ ] 4.1 Write property test for FHIR JSON specification compliance
  - **Property 1: FHIR JSON specification compliance**
  - **Validates: Requirements 1.1**

- [ ] 4.2 Write property test for resourceType inclusion
  - **Property 2: ResourceType inclusion**
  - **Validates: Requirements 1.2**

- [ ] 4.3 Write property test for correct class instantiation
  - **Property 6: Correct class instantiation**
  - **Validates: Requirements 2.1**

- [ ] 4.4 Write property test for resourceType-based class resolution
  - **Property 7: ResourceType-based class resolution**
  - **Validates: Requirements 2.2**

- [ ] 4.5 Write property test for resource normalizer selection
  - **Property 11: Resource normalizer selection**
  - **Validates: Requirements 3.1**

- [ ] 4.6 Write property test for discriminator map type resolution
  - **Property 16: Discriminator map type resolution**
  - **Validates: Requirements 4.1**

- [ ] 5. Implement FHIRComplexTypeNormalizer
  - Create normalizer for FHIR complex type classes (Address, HumanName, etc.)
  - Handle nested object serialization and deserialization
  - Implement choice element (value[x]) pattern support
  - Add complex type extension handling
  - Support polymorphic complex type resolution
  - _Requirements: 1.1, 2.1, 3.2, 4.2, 4.3_

- [ ] 5.1 Write property test for complex type normalizer selection
  - **Property 12: Complex type normalizer selection**
  - **Validates: Requirements 3.2**

- [ ] 5.2 Write property test for choice element type suffix
  - **Property 17: Choice element type suffix**
  - **Validates: Requirements 4.2**

- [ ] 5.3 Write property test for polymorphic reference handling
  - **Property 18: Polymorphic reference handling**
  - **Validates: Requirements 4.3**

- [ ] 6. Implement FHIRPrimitiveTypeNormalizer
  - Create normalizer for FHIR primitive types with extension support
  - Implement underscore notation for primitive extensions in JSON
  - Handle primitive value validation and type conversion
  - Add XML attribute serialization support for primitives
  - Support primitive extension round-trip preservation
  - _Requirements: 1.3, 2.3, 3.3, 5.3, 7.5_

- [ ] 6.1 Write property test for primitive extension underscore notation
  - **Property 3: Primitive extension underscore notation**
  - **Validates: Requirements 1.3**

- [ ] 6.2 Write property test for primitive extension deserialization
  - **Property 8: Primitive extension deserialization**
  - **Validates: Requirements 2.3**

- [ ] 6.3 Write property test for primitive type normalizer selection
  - **Property 13: Primitive type normalizer selection**
  - **Validates: Requirements 3.3**

- [ ] 6.4 Write property test for XML primitive extension serialization
  - **Property 23: XML primitive extension serialization**
  - **Validates: Requirements 5.3**

- [ ] 6.5 Write property test for primitive extension round-trip
  - **Property 31: Primitive extension round-trip**
  - **Validates: Requirements 7.5**

- [ ] 7. Implement FHIRBackboneElementNormalizer
  - Create normalizer for backbone elements within resources
  - Handle backbone element extensions and modifierExtensions
  - Support nested backbone element structures
  - Maintain parent-child relationships during serialization
  - Add backbone element metadata handling
  - _Requirements: 1.1, 2.1, 3.4, 7.4_

- [ ] 7.1 Write property test for backbone element normalizer selection
  - **Property 14: Backbone element normalizer selection**
  - **Validates: Requirements 3.4**

- [ ] 7.2 Write property test for nested structure preservation
  - **Property 30: Nested structure preservation**
  - **Validates: Requirements 7.4**

- [ ] 8. Implement discriminator map resolver
  - Create DiscriminatorMapResolver for polymorphic type resolution
  - Add resourceType-based resolution for resources
  - Implement choice element type resolution (value[x] patterns)
  - Add reference type resolution for polymorphic references
  - Support extension value type resolution
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

- [ ] 8.1 Write property test for polymorphic extension value serialization
  - **Property 19: Polymorphic extension value serialization**
  - **Validates: Requirements 4.4**

- [ ] 8.2 Write property test for polymorphic type deserialization
  - **Property 20: Polymorphic type deserialization**
  - **Validates: Requirements 4.5**

- [ ] 9. Add comprehensive JSON serialization support
  - Implement sparse extension array handling for JSON
  - Add null value omission according to FHIR rules
  - Handle unknown property processing with configurable policies
  - Implement automatic normalizer selection logic
  - Add JSON format validation and error handling
  - _Requirements: 1.4, 1.5, 2.4, 2.5, 3.5_

- [ ] 9.1 Write property test for sparse extension array handling
  - **Property 4: Sparse extension array handling**
  - **Validates: Requirements 1.4**

- [ ] 9.2 Write property test for null value omission
  - **Property 5: Null value omission**
  - **Validates: Requirements 1.5**

- [ ] 9.3 Write property test for configurable unknown property handling
  - **Property 9: Configurable unknown property handling**
  - **Validates: Requirements 2.4**

- [ ] 9.4 Write property test for invalid JSON exception handling
  - **Property 10: Invalid JSON exception handling**
  - **Validates: Requirements 2.5**

- [ ] 9.5 Write property test for automatic normalizer selection
  - **Property 15: Automatic normalizer selection**
  - **Validates: Requirements 3.5**

- [ ] 10. Checkpoint - Ensure JSON serialization tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 11. Implement XML serialization support
  - Create XML-specific serialization logic for all normalizers
  - Add FHIR namespace declaration handling
  - Implement XML primitive extension serialization (attributes and child elements)
  - Add XML deserialization support
  - Implement optional XML schema validation
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [ ] 11.1 Write property test for FHIR XML specification compliance
  - **Property 21: FHIR XML specification compliance**
  - **Validates: Requirements 5.1**

- [ ] 11.2 Write property test for XML namespace inclusion
  - **Property 22: XML namespace inclusion**
  - **Validates: Requirements 5.2**

- [ ] 11.3 Write property test for XML deserialization accuracy
  - **Property 24: XML deserialization accuracy**
  - **Validates: Requirements 5.4**

- [ ] 11.4 Write property test for XML schema validation
  - **Property 25: XML schema validation**
  - **Validates: Requirements 5.5**

- [ ] 12. Implement serialization configuration system
  - Create FHIRSerializationContext for configuration options
  - Add support for JSON and XML format selection
  - Implement strict and lenient validation modes
  - Add configurable unknown element policies (ignore, error, preserve)
  - Create performance optimization options
  - Add debugging and error context support
  - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

- [ ] 12.1 Write property test for format support
  - **Property 37: Format support**
  - **Validates: Requirements 9.1**

- [ ] 12.2 Write property test for validation mode support
  - **Property 38: Validation mode support**
  - **Validates: Requirements 9.2**

- [ ] 12.3 Write property test for unknown element policy enforcement
  - **Property 39: Unknown element policy enforcement**
  - **Validates: Requirements 9.3**

- [ ] 12.4 Write property test for performance optimization options
  - **Property 40: Performance optimization options**
  - **Validates: Requirements 9.4**

- [ ] 12.5 Write property test for debug information availability
  - **Property 41: Debug information availability**
  - **Validates: Requirements 9.5**

- [ ] 13. Implement comprehensive round-trip testing
  - Add round-trip property tests for all FHIR types
  - Test extension data preservation through serialization cycles
  - Verify metadata preservation in round-trip operations
  - Test nested structure preservation
  - Validate primitive extension round-trip behavior
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 13.1 Write property test for object equivalence preservation
  - **Property 27: Object equivalence preservation**
  - **Validates: Requirements 7.1**

- [ ] 13.2 Write property test for extension data preservation
  - **Property 28: Extension data preservation**
  - **Validates: Requirements 7.2**

- [ ] 13.3 Write property test for metadata preservation
  - **Property 29: Metadata preservation**
  - **Validates: Requirements 7.3**

- [ ] 14. Update FHIR class generation to include attributes
  - Modify FHIRModelGenerator to add appropriate attributes to generated classes
  - Add FHIRResource attributes to resource classes with resourceType
  - Add FHIRComplexType attributes to complex type classes
  - Add FHIRPrimitive attributes to primitive type classes
  - Add FHIRBackboneElement attributes to backbone element classes
  - Ensure attributes are simple and reusable across model types
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ] 15. Create Symfony service configuration
  - Configure normalizers as Symfony services with proper tags
  - Set up dependency injection for metadata extractor and cache
  - Configure discriminator map resolver as a service
  - Add service aliases for easy access to FHIR serialization components
  - Create service configuration for different FHIR versions
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ] 16. Add comprehensive error handling
  - Create FHIR-specific serialization exception hierarchy
  - Implement detailed error context with element paths
  - Add validation error reporting with meaningful messages
  - Create error recovery strategies for non-critical failures
  - Implement logging for serialization errors and warnings
  - _Requirements: 2.5, 9.5_

- [ ] 17. Create integration tests with official FHIR test data
  - Set up integration with FHIR test cases repository
  - Create tests using official FHIR examples
  - Test serialization against FHIR conformance requirements
  - Validate both JSON and XML format compliance
  - Test edge cases and boundary conditions from official test suite
  - _Requirements: 1.1, 5.1, 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 18. Final checkpoint - Comprehensive testing
  - Ensure all tests pass, ask the user if questions arise.
  - Run complete test suite including property-based tests
  - Verify serialization works with real FHIR data
  - Validate performance with large FHIR resources
  - Confirm all requirements are met and documented