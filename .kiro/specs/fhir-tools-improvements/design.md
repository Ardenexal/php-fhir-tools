# Design Document

## Overview

This design document outlines the architectural improvements for the PHP FHIR Tools project to address type safety issues, enhance error handling, add comprehensive testing, improve package management, and provide better code generation capabilities. The improvements will transform the current basic FHIR class generator into a robust, production-ready tool with comprehensive validation, monitoring, and extensibility features.

## Architecture

The enhanced FHIR Tools will maintain the existing Symfony Console architecture while adding several new layers and components:

```
┌─────────────────────────────────────────────────────────────┐
│                    CLI Interface Layer                      │
├─────────────────────────────────────────────────────────────┤
│  Enhanced Commands │ Progress Indicators │ Configuration    │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                   Service Layer                             │
├─────────────────────────────────────────────────────────────┤
│ Generation │ Validation │ Package Mgmt │ Logging │ Metrics  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                   Core Domain Layer                         │
├─────────────────────────────────────────────────────────────┤
│ Type System │ Builder Context │ Code Generation │ Templates │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                Infrastructure Layer                         │
├─────────────────────────────────────────────────────────────┤
│ File System │ HTTP Client │ Cache │ Package Registry │ I/O  │
└─────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Enhanced Type System

**TypeDefinitionRegistry**
- Manages all FHIR type definitions with proper generic type annotations
- Resolves circular dependencies using forward declarations
- Provides type-safe access to structure definitions

**TypeResolver**
- Converts FHIR types to PHP types with complete generic specifications
- Handles nullable types and array types correctly
- Manages inheritance relationships and polymorphic types

### 2. Robust Error Handling System

**FHIRToolsException Hierarchy**
```php
abstract class FHIRToolsException extends Exception
├── PackageException (package loading/validation errors)
├── GenerationException (code generation errors)  
├── ValidationException (FHIR validation errors)
└── ConfigurationException (configuration errors)
```

**ErrorCollector**
- Aggregates multiple validation errors
- Provides detailed error context with element paths
- Supports error recovery strategies

### 3. Enhanced Package Management

**PackageManager**
- Handles semantic version resolution
- Manages dependency chains and conflicts
- Provides package integrity verification
- Supports multiple package registries

**PackageCache**
- Implements cache integrity checks with checksums
- Provides cache invalidation strategies
- Supports concurrent access with file locking

### 4. Comprehensive Testing Framework

**TestGenerator**
- Generates unit tests for each FHIR class
- Creates property-based tests for validation rules
- Implements serialization round-trip tests
- Generates integration tests for complex scenarios

**ValidationTestSuite**
- Tests constraint enforcement
- Validates inheritance relationships
- Verifies enum case generation accuracy

### 5. Advanced Code Generation

**EnhancedCodeGenerator**
- Generates comprehensive PHPDoc annotations
- Implements proper constructor chaining for inheritance using constructor property promotion
- Creates Symfony Serializer normalizers and denormalizers for JSON conversion
- Adds Symfony validator constraints
- Organizes output by StructureDefinition kind (resource, complex-type, primitive-type)
- Groups backbone elements in dedicated folders per resource

**TemplateEngine**
- Provides customizable code templates
- Supports namespace and directory customization
- Enables selective generation by resource type

### 6. Observability and Monitoring

**Logger**
- Structured logging with context information
- Performance metrics collection
- Debug mode with intermediate state output
- Error tracking with stack traces

**ProgressReporter**
- Real-time progress indicators
- Memory usage monitoring
- Generation statistics and timing

## Data Models

### Enhanced BuilderContext

```php
class BuilderContext
{
    private TypeDefinitionRegistry $typeRegistry;
    private ErrorCollector $errorCollector;
    private MetricsCollector $metrics;
    private array<string, NamespaceManager> $namespaceManagers;
    private DependencyResolver $dependencyResolver;
    private OutputOrganizer $outputOrganizer;
}
```

### OutputOrganizer

```php
class OutputOrganizer
{
    public function getOutputPath(StructureDefinition $definition): string;
    public function organizeByKind(string $kind): string; // resource, complex-type, primitive-type
    public function getBackboneElementPath(string $resourceName, string $elementPath): string;
}
```

### PackageMetadata

```php
class PackageMetadata
{
    public readonly string $name;
    public readonly string $version;
    public readonly array $fhirVersions;
    public readonly array $dependencies;
    public readonly string $checksum;
    public readonly DateTime $downloadedAt;
}
```

### GenerationConfiguration

```php
class GenerationConfiguration
{
    public readonly string $outputDirectory;
    public readonly string $baseNamespace;
    public readonly array $includedResourceTypes;
    public readonly array $excludedResourceTypes;
    public readonly bool $generateTests;
    public readonly bool $strictValidation;
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

Based on the prework analysis, the following correctness properties will ensure the system behaves correctly:

### Type Safety Properties

**Property 1: PHPStan compliance**
*For any* generated code and system code, running PHPStan analysis should return exit code 0 with zero errors
**Validates: Requirements 1.1**

**Property 2: Circular dependency resolution**
*For any* set of FHIR definitions with circular type references, the system should generate valid PHP code without circular dependencies
**Validates: Requirements 1.2**

**Property 3: Complete generic type annotations**
*For any* method parameter using arrays, the generated code should include complete generic type specifications with value types
**Validates: Requirements 1.3**

**Property 4: Null safety**
*For any* nullable value processing, the system should generate null checks before method calls on potentially null values
**Validates: Requirements 1.4**

**Property 5: Safe array access**
*For any* array offset access, the system should generate existence checks before accessing array keys
**Validates: Requirements 1.5**

### Error Handling Properties

**Property 6: Exception specificity**
*For any* invalid FHIR definition input, the system should throw specific exceptions with detailed error messages containing element context
**Validates: Requirements 2.1**

**Property 7: Required key validation**
*For any* required array key access, the system should validate key existence before attempting access
**Validates: Requirements 2.2**

**Property 8: Network resilience**
*For any* package download failure, the system should implement retry mechanisms with exponential backoff up to a maximum number of attempts
**Validates: Requirements 2.3**

**Property 9: File system error handling**
*For any* file system operation failure, the system should handle exceptions gracefully and provide recovery options
**Validates: Requirements 2.4**

**Property 10: Version conflict detection**
*For any* set of packages with incompatible FHIR versions, the system should detect and report version conflicts before generation
**Validates: Requirements 2.5**

### Testing Properties

**Property 11: Test generation completeness**
*For any* generated FHIR class, the system should create corresponding unit test files with appropriate test cases
**Validates: Requirements 3.1**

**Property 12: Enum case accuracy**
*For any* value set converted to enum, all enum cases should accurately reflect the value set definitions
**Validates: Requirements 3.2**

**Property 13: Structural relationship correctness**
*For any* complex nested FHIR structure, the generated code should maintain correct property relationships and inheritance hierarchies
**Validates: Requirements 3.3**

**Property 14: Serialization round-trip**
*For any* generated FHIR class instance, serializing to JSON and deserializing back should produce an equivalent object
**Validates: Requirements 3.4**

**Property 15: Validation constraint enforcement**
*For any* FHIR validation rule, the generated Symfony validator constraints should properly enforce the rule
**Validates: Requirements 3.5**

### Package Management Properties

**Property 16: Semantic version support**
*For any* semantic version range specification, the system should correctly select packages within the specified range
**Validates: Requirements 4.1**

**Property 17: Dependency resolution**
*For any* package with dependencies, the system should resolve and install the complete dependency chain without conflicts
**Validates: Requirements 4.2**

**Property 18: Cache integrity**
*For any* cached package, the system should verify integrity using checksums before reuse
**Validates: Requirements 4.3**

**Property 19: Version isolation**
*For any* multiple FHIR versions used simultaneously, generated classes should be properly isolated by version namespace
**Validates: Requirements 4.4**

**Property 20: Metadata completeness**
*For any* accessed package, all required metadata fields should be present and accurate
**Validates: Requirements 4.5**

### Code Generation Properties

**Property 21: Documentation completeness**
*For any* generated class, comprehensive PHPDoc annotations should be present for all public methods and properties
**Validates: Requirements 5.1**

**Property 22: Constructor inheritance**
*For any* inheritance relationship, child class constructors should properly call parent constructors with correct parameters
**Validates: Requirements 5.2**

**Property 23: Optional property nullability**
*For any* optional FHIR property, the generated PHP property should be correctly marked as nullable
**Validates: Requirements 5.3**

**Property 24: Validation constraint generation**
*For any* FHIR validation rule, appropriate Symfony validator constraints should be generated and applied
**Validates: Requirements 5.4**

**Property 25: Symfony Serializer integration**
*For any* generated class requiring serialization, appropriate Symfony Serializer normalizers and denormalizers should be generated for JSON conversion
**Validates: Requirements 5.5**

### Observability Properties

**Property 26: Logging completeness**
*For any* generation process execution, detailed progress information should be logged at appropriate levels
**Validates: Requirements 6.1**

**Property 27: Error context capture**
*For any* error occurrence, complete stack traces and contextual information should be captured in logs
**Validates: Requirements 6.2**

**Property 28: Performance metrics**
*For any* generation process, timing and memory usage metrics should be collected and made available
**Validates: Requirements 6.3**

**Property 29: Validation error specificity**
*For any* validation failure, specific error messages with element paths should be logged
**Validates: Requirements 6.4**

**Property 30: Debug state output**
*For any* debug mode execution, intermediate generation states should be output for troubleshooting
**Validates: Requirements 6.5**

### CLI Interface Properties

**Property 31: Progress indication**
*For any* CLI command execution, clear progress indicators and status updates should be displayed to the user
**Validates: Requirements 7.1**

**Property 32: Configuration file support**
*For any* configuration file provided, generation settings should be properly loaded and applied
**Validates: Requirements 7.2**

**Property 33: Output customization**
*For any* custom namespace or directory specification, the generated output should respect the customization
**Validates: Requirements 7.3**

**Property 34: Selective generation**
*For any* resource type or profile filter specification, only the specified items should be generated
**Validates: Requirements 7.4**

**Property 35: Validation mode support**
*For any* validation mode specification (strict/permissive), the system should behave according to the specified mode
**Validates: Requirements 7.5**

<function_calls>
<invoke name="prework">
<parameter name="featureName">fhir-tools-improvements

## Error Handling

The enhanced error handling system will provide comprehensive error management with specific exception types, detailed error context, and recovery mechanisms:

### Exception Hierarchy
- **FHIRToolsException**: Base exception with error codes and context
- **PackageException**: Package loading, validation, and dependency issues
- **GenerationException**: Code generation failures with element context
- **ValidationException**: FHIR validation errors with path information
- **ConfigurationException**: Configuration and setup errors

### Error Recovery Strategies
- **Retry Mechanisms**: Exponential backoff for network operations
- **Graceful Degradation**: Continue generation with warnings for non-critical errors
- **Error Aggregation**: Collect multiple validation errors before failing
- **Context Preservation**: Maintain full error context for debugging

## Testing Strategy

The testing approach will combine unit testing and property-based testing to ensure comprehensive coverage:

### Unit Testing
- **Component Tests**: Test individual classes and methods with specific examples
- **Integration Tests**: Test component interactions and end-to-end workflows
- **Edge Case Tests**: Test boundary conditions and error scenarios
- **Regression Tests**: Prevent reintroduction of fixed bugs

### Property-Based Testing
- **Universal Properties**: Test properties that should hold across all inputs using **PHPUnit with Eris** for property-based testing
- **Invariant Testing**: Verify system invariants are maintained during operations
- **Round-trip Testing**: Ensure serialization/deserialization preserves data integrity
- **Constraint Validation**: Verify FHIR validation rules are properly enforced

### Test Configuration
- **Minimum 100 iterations** for each property-based test to ensure thorough coverage
- **Test tagging** with explicit references to design document properties using format: `**Feature: fhir-tools-improvements, Property {number}: {property_text}**`
- **Automated test generation** for generated FHIR classes
- **Performance benchmarks** for generation speed and memory usage

### Testing Framework Requirements
- Use **PHPUnit 12+** as the primary testing framework
- Integrate **Eris** for property-based testing capabilities
- Generate tests automatically for all generated FHIR classes
- Implement custom generators for FHIR-specific data structures
- Create test utilities for common FHIR testing patterns
- Use **FHIR Test Cases** (https://github.com/FHIR/fhir-test-cases) as the basis for validation and conformance testing
- Leverage official FHIR test data for comprehensive validation coverage

## Code Generation Principles

### Constructor Property Promotion
All generated FHIR value objects will use PHP 8+ constructor property promotion exclusively:
- No separate property declarations in class bodies
- All properties defined in constructor parameters with visibility modifiers
- Maintains clean, minimal class structure focused on data representation

### Symfony Serializer Integration
- Generate custom Normalizers and Denormalizers for JSON conversion
- Support for FHIR-specific serialization rules (e.g., resource type handling)
- Future extensibility for XML serialization support
- Proper handling of FHIR data types and constraints

### Output Organization
Generated classes will be organized by StructureDefinition kind:
```
output/
├── Resource/           # kind: resource
│   ├── Patient.php
│   ├── Observation.php
│   └── Patient/        # backbone elements
│       ├── Contact.php
│       └── Communication.php
├── ComplexType/        # kind: complex-type
│   ├── Address.php
│   ├── HumanName.php
│   └── Coding.php
└── PrimitiveType/      # kind: primitive-type
    ├── FHIRString.php
    ├── FHIRBoolean.php
    └── FHIRInteger.php
```

### Attribute Usage Policy
- Request approval before creating new PHP attributes for FHIR models
- Minimize attribute usage to maintain clean generated code
- Focus on essential metadata only (existing FhirResource attribute)
- Document any new attributes with clear justification

### Test Data Integration
- Leverage official FHIR test cases from https://github.com/FHIR/fhir-test-cases
- Use real-world FHIR examples for validation testing
- Ensure generated classes can handle all official test scenarios
- Maintain compatibility with FHIR conformance requirements