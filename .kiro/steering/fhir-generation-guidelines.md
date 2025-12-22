---
inclusion: always
---

# FHIR Code Generation Guidelines

## Component Architecture

### CodeGeneration Component Usage
- **Standalone Usage**: Component can be used independently without Symfony
- **Bundle Integration**: Seamlessly integrates with FHIRBundle for Symfony applications
- **Service Injection**: Use dependency injection for FHIRModelGenerator and related services
- **Component Boundaries**: Maintain clear separation between CodeGeneration and other components

### Namespace Organization
- **Component Namespace**: Use `Ardenexal\FHIRTools\Component\CodeGeneration\*` for new code
- **Legacy Compatibility**: Maintain backward compatibility aliases for existing code
- **Generated Code Namespaces**: Use configurable base namespaces for generated FHIR classes
- **Interface Definitions**: Define clear interfaces in component namespace

## FHIR-Specific Requirements

### StructureDefinition Processing
- **Validation**: Always validate FHIR StructureDefinitions before processing
- **Error Handling**: Use component-specific `ErrorCollector` to accumulate validation errors
- **Retry Logic**: Implement retry mechanisms for network operations using `RetryHandler`
- **Package Loading**: Use `PackageLoader` from CodeGeneration component for consistent FHIR package management

### Generated Class Structure
- **Namespace**: All generated classes should use configurable namespace patterns
- **Properties**: Map FHIR elements to PHP properties with appropriate types
- **Methods**: Generate getters/setters following PHP conventions
- **Validation**: Include validation methods for FHIR constraints
- **Serialization**: Support JSON serialization/deserialization through Serialization component
- **Component Integration**: Ensure generated classes work with Serialization component

### FHIR Data Types
- **Primitive Types**: Map FHIR primitives to PHP scalar types
- **Complex Types**: Generate separate classes for complex FHIR types
- **References**: Handle FHIR references with proper type safety
- **Extensions**: Support FHIR extension mechanisms
- **Cardinality**: Respect FHIR cardinality constraints (0..1, 0..*, 1..1, 1..*)

### Code Generation Best Practices
- **Template-Based**: Use consistent templates for generated code
- **Incremental**: Support incremental generation to avoid regenerating unchanged files
- **Metadata**: Include generation metadata in file headers
- **Validation**: Validate generated code syntax before writing to disk
- **Component Compatibility**: Ensure generated code is compatible with Serialization component

## Service Integration

### Symfony Integration
- **Bundle Services**: Use FHIRBundle services when available
- **Standalone Services**: Provide standalone service instantiation
- **Configuration**: Support both bundle configuration and direct configuration
- **Dependency Injection**: Properly configure services in Symfony container

### Component Communication
- **Interface-Based**: Use interfaces for communication between components
- **Loose Coupling**: Maintain loose coupling between CodeGeneration and other components
- **Shared Types**: Use shared interfaces and types where appropriate
- **Error Propagation**: Properly propagate errors between components

## Output Management
- **Directory Structure**: Organize generated files in logical directory hierarchies
- **File Naming**: Use consistent naming conventions for generated files
- **Cleanup**: Remove obsolete generated files when structures change
- **Backup**: Consider backup strategies for existing generated code
- **Component Isolation**: Keep generated code separate from component source code

## Performance Considerations
- **Memory Usage**: Monitor memory usage during large FHIR package processing
- **Batch Processing**: Process multiple StructureDefinitions efficiently
- **Caching**: Cache processed definitions to avoid redundant work
- **Progress Reporting**: Provide progress feedback for long-running operations
- **Component Loading**: Load components only when needed

## Testing Guidelines
- **Component Testing**: Test CodeGeneration component independently
- **Integration Testing**: Test integration with other components
- **Generated Code Testing**: Validate generated code functionality
- **Performance Testing**: Test performance with large FHIR packages
- **Cross-Component Testing**: Test interaction with Serialization component