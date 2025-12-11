---
inclusion: always
---

# FHIR Code Generation Guidelines

## FHIR-Specific Requirements

### StructureDefinition Processing
- **Validation**: Always validate FHIR StructureDefinitions before processing
- **Error Handling**: Use `ErrorCollector` to accumulate validation errors
- **Retry Logic**: Implement retry mechanisms for network operations using `RetryHandler`
- **Package Loading**: Use `PackageLoader` for consistent FHIR package management

### Generated Class Structure
- **Namespace**: All generated classes should use consistent namespace patterns
- **Properties**: Map FHIR elements to PHP properties with appropriate types
- **Methods**: Generate getters/setters following PHP conventions
- **Validation**: Include validation methods for FHIR constraints
- **Serialization**: Support JSON serialization/deserialization

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

## Output Management
- **Directory Structure**: Organize generated files in logical directory hierarchies
- **File Naming**: Use consistent naming conventions for generated files
- **Cleanup**: Remove obsolete generated files when structures change
- **Backup**: Consider backup strategies for existing generated code

## Performance Considerations
- **Memory Usage**: Monitor memory usage during large FHIR package processing
- **Batch Processing**: Process multiple StructureDefinitions efficiently
- **Caching**: Cache processed definitions to avoid redundant work
- **Progress Reporting**: Provide progress feedback for long-running operations