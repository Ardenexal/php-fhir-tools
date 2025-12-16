# Implementation Plan

- [ ] 1. Create multi-project directory structure


  - Create src/Bundle/FHIRBundle/ directory with proper Symfony bundle structure
  - Create src/Component/CodeGeneration/ directory for code generation component
  - Create src/Component/Serialization/ directory for serialization component
  - Set up proper PSR-4 namespace organization for each component
  - _Requirements: 1.1, 1.5_

- [x] 1.1 Create component composer.json files



  - Write composer.json for FHIRBundle with symfony-bundle type and proper dependencies
  - Write composer.json for CodeGeneration component with library type and minimal dependencies
  - Write composer.json for Serialization component with library type and Symfony dependencies
  - Define explicit inter-component dependencies in composer.json files
  - _Requirements: 1.2, 1.4_

- [ ] 1.2 Write property test for component composer.json validity
  - **Property 1: Component composer.json validity**
  - **Validates: Requirements 1.2**

- [ ] 1.3 Write property test for dependency separation
  - **Property 2: Dependency separation**
  - **Validates: Requirements 1.3**

- [ ] 1.4 Write property test for explicit dependency declaration
  - **Property 3: Explicit dependency declaration**
  - **Validates: Requirements 1.4**

- [ ] 1.5 Write property test for PSR-4 namespace compliance
  - **Property 4: PSR-4 namespace compliance**
  - **Validates: Requirements 1.5**

- [x] 2. Implement FHIRBundle with Symfony best practices
  - Create FHIRBundle.php extending Symfony Bundle class
  - Implement FHIRExtension for semantic configuration
  - Create FHIRBundleConfiguration with configuration tree builder
  - Set up Resources/config/services.yaml with YAML service definitions
  - Create DependencyInjection compiler passes if needed
  - _Requirements: 2.1, 2.2, 2.5_

- [x] 2.1 Configure bundle dependency injection
  - Register FHIR services in the Symfony service container
  - Configure service autowiring and autoconfiguration
  - Set up service aliases and public services as needed
  - Implement proper service tagging for FHIR services
  - _Requirements: 2.3, 2.4_

- [x] 2.2 Write property test for bundle service registration
  - **Property 5: Bundle service registration**
  - **Validates: Requirements 2.3**

- [x] 2.3 Write property test for service container accessibility
  - **Property 6: Service container accessibility**
  - **Validates: Requirements 2.4**

- [x] 2.4 Write property test for configuration schema validation
  - **Property 7: Configuration schema validation**
  - **Validates: Requirements 2.5**

- [x] 3. Ensure Symfony 6.4 and 7.4 cross-compatibility
  - Update all composer.json files with version ranges supporting both Symfony versions
  - Test bundle configuration syntax compatibility across versions
  - Handle deprecated features gracefully with version-specific code paths
  - Validate service container configuration works in both versions
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 3.1 Write property test for Symfony 6.4 compatibility
  - **Property 8: Symfony 6.4 compatibility**
  - **Validates: Requirements 3.1**

- [x] 3.2 Write property test for Symfony 7.4 compatibility
  - **Property 9: Symfony 7.4 compatibility**
  - **Validates: Requirements 3.2**

- [x] 3.3 Write property test for version range validity
  - **Property 10: Version range validity**
  - **Validates: Requirements 3.3**

- [x] 3.4 Write property test for configuration compatibility
  - **Property 11: Configuration compatibility**
  - **Validates: Requirements 3.4**

- [x] 3.5 Write property test for deprecation handling
  - **Property 12: Deprecation handling**
  - **Validates: Requirements 3.5**

- [ ] 4. Create Symfony Flex recipe for automatic configuration
  - Create config/recipes/fhir-bundle/ directory structure
  - Write manifest.json with bundle registration and file copying instructions
  - Create default config/packages/fhir.yaml configuration file
  - Set up environment variable templates for bundle configuration
  - Test recipe installation and automatic bundle registration
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

- [ ] 5. Checkpoint - Verify basic structure and configuration
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Migrate existing code to CodeGeneration component
  - Move FHIRModelGenerator, FHIRValueSetGenerator to CodeGeneration component
  - Move Package/ directory classes to CodeGeneration component
  - Move BuilderContext and related generation classes
  - Update namespaces from Ardenexal\FHIRTools\ to Ardenexal\FHIRTools\Component\CodeGeneration\
  - Update all import statements and class references
  - _Requirements: 6.3, 7.1, 7.2_

- [ ] 6.1 Create CodeGeneration component interfaces and services
  - Define clear interfaces for code generation functionality
  - Implement service classes with minimal external dependencies
  - Create proper exception hierarchy for CodeGeneration component
  - Set up component-specific configuration and options
  - _Requirements: 7.2, 7.3_

- [ ] 6.2 Write property test for CodeGeneration independence
  - **Property 18: CodeGeneration independence**
  - **Validates: Requirements 7.2**

- [ ] 6.3 Write property test for CodeGeneration dependency minimization
  - **Property 19: CodeGeneration dependency minimization**
  - **Validates: Requirements 7.3**

- [ ] 6.4 Write property test for CodeGeneration test coverage
  - **Property 20: CodeGeneration test coverage**
  - **Validates: Requirements 7.4**

- [ ] 7. Migrate existing code to Serialization component
  - Move all Serialization/ directory classes to Serialization component
  - Move FHIR serialization services and normalizers
  - Move validation and schema validation classes
  - Update namespaces from Ardenexal\FHIRTools\Serialization\ to Ardenexal\FHIRTools\Component\Serialization\
  - Update all import statements and class references
  - _Requirements: 6.3, 8.1, 8.2_

- [ ] 7.1 Create Serialization component interfaces and Symfony integration
  - Define clear interfaces for serialization functionality
  - Implement Symfony Serializer integration with normalizers and denormalizers
  - Create FHIR validation capabilities within the component
  - Optimize performance for large FHIR document processing
  - _Requirements: 8.2, 8.3, 8.4, 8.5_

- [ ] 7.2 Write property test for Serialization independence
  - **Property 21: Serialization independence**
  - **Validates: Requirements 8.2**

- [ ] 7.3 Write property test for Serialization Symfony integration
  - **Property 22: Serialization Symfony integration**
  - **Validates: Requirements 8.3**

- [ ] 7.4 Write property test for Serialization validation capabilities
  - **Property 23: Serialization validation capabilities**
  - **Validates: Requirements 8.4**

- [ ] 7.5 Write property test for Serialization performance
  - **Property 24: Serialization performance**
  - **Validates: Requirements 8.5**

- [ ] 8. Implement backward compatibility layer
  - Create class aliases for all moved classes to maintain backward compatibility
  - Set up autoloader registration for compatibility aliases
  - Update root composer.json to include compatibility autoloading
  - Test that existing code continues to work with old namespace references
  - _Requirements: 6.1, 6.2_

- [ ] 8.1 Write property test for functional preservation
  - **Property 13: Functional preservation**
  - **Validates: Requirements 6.1**

- [ ] 8.2 Write property test for namespace backward compatibility
  - **Property 14: Namespace backward compatibility**
  - **Validates: Requirements 6.2**

- [ ] 8.3 Write property test for import resolution
  - **Property 15: Import resolution**
  - **Validates: Requirements 6.3**

- [ ] 9. Migrate and update test suite
  - Move tests to appropriate component directories
  - Update test namespaces and imports to match new structure
  - Create component-specific test suites
  - Ensure all existing tests continue to pass
  - Add integration tests for cross-component functionality
  - _Requirements: 6.4, 6.5_

- [ ] 9.1 Write property test for test preservation
  - **Property 16: Test preservation**
  - **Validates: Requirements 6.4**

- [ ] 9.2 Write property test for build process preservation
  - **Property 17: Build process preservation**
  - **Validates: Requirements 6.5**

- [ ] 10. Update documentation and steering files
  - Update AGENTS.md to reflect new multi-project structure
  - Create README.md files for FHIRBundle, CodeGeneration, and Serialization components
  - Update all steering files to reference new component structure
  - Create architecture documentation in /docs folder
  - Write migration guide for developers
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_

- [ ] 10.1 Create comprehensive documentation structure
  - Create /docs/architecture.md explaining the new multi-project structure
  - Create /docs/migration-guide.md with step-by-step migration instructions
  - Create /docs/component-guides/ directory with individual component documentation
  - Update contribution guidelines to cover multi-package development
  - _Requirements: 5.3, 5.4, 5.5, 5.7_

- [ ] 11. Update build and deployment processes
  - Update composer scripts to work with new component structure
  - Modify CI/CD pipeline to test components independently and together
  - Update PHPStan configuration to analyze all components
  - Ensure code style tools work across all components
  - Test package installation and Flex recipe functionality
  - _Requirements: 6.5_

- [ ] 12. Final integration testing and validation
  - Run complete test suite across all components
  - Test Symfony Flex recipe installation in fresh Symfony applications
  - Validate cross-component integration works correctly
  - Test backward compatibility with existing codebases
  - Verify performance is maintained after reorganization
  - _Requirements: 6.1, 6.4, 6.5_

- [ ] 13. Final checkpoint - Comprehensive validation
  - Ensure all tests pass, ask the user if questions arise.
  - Verify all requirements are met and documented
  - Confirm repository reorganization is complete and functional
  - Validate that both Symfony 6.4 and 7.4 compatibility is maintained