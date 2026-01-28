---
inclusion: always
---

# Error Handling and Exception Patterns

## Exception Hierarchy

### Project-Specific Exceptions
All exceptions should extend from project-specific base exceptions in `src/Exception/`:
- **FHIRToolsException**: Base exception for all project exceptions
- **GenerationException**: For FHIR code generation errors
- **ValidationException**: For FHIR validation errors
- **PackageException**: For FHIR package loading errors

### Exception Design Principles
- **Meaningful Messages**: Provide clear, actionable error messages
- **Context Information**: Include relevant context (file names, line numbers, etc.)
- **Error Codes**: Use consistent error codes for programmatic handling
- **Chain Exceptions**: Preserve original exception context when re-throwing

## Error Collection and Reporting

### ErrorCollector Usage
- **Accumulate Errors**: Use `ErrorCollector` to gather multiple validation errors
- **Batch Reporting**: Report all errors at once rather than failing on first error
- **Severity Levels**: Distinguish between warnings, errors, and critical failures
- **Context Preservation**: Maintain context about where errors occurred

### Error Message Standards
```php
// Good: Specific and actionable
throw new ValidationException(
    'Invalid cardinality "2..0" in element "Patient.name" at line 45: minimum cannot exceed maximum'
);

// Bad: Vague and unhelpful
throw new Exception('Validation failed');
```

## Retry Mechanisms

### RetryHandler Integration
- **Network Operations**: Use retry logic for HTTP requests and file downloads
- **Transient Failures**: Retry operations that may fail due to temporary issues
- **Exponential Backoff**: Implement exponential backoff for retry delays
- **Max Attempts**: Set reasonable maximum retry attempts

### Retry Strategy
```php
$retryHandler->execute(
    operation: fn() => $this->downloadFHIRPackage($url),
    maxAttempts: 3,
    backoffMultiplier: 2.0,
    initialDelay: 1000 // milliseconds
);
```

## Logging and Monitoring

### Error Logging
- **Structured Logging**: Use structured log formats for better parsing
- **Log Levels**: Use appropriate log levels (ERROR, WARNING, INFO, DEBUG)
- **Context Data**: Include relevant context in log entries
- **No Sensitive Data**: Never log sensitive information

### Monitoring Integration
- **Error Tracking**: Integrate with error tracking services when available
- **Metrics**: Track error rates and types for monitoring
- **Alerting**: Set up alerts for critical error conditions
- **Performance**: Monitor error handling performance impact

## Validation Error Handling

### FHIR Validation Errors
- **Schema Validation**: Handle JSON schema validation errors
- **Business Rule Validation**: Validate FHIR business rules and constraints
- **Reference Validation**: Validate FHIR resource references
- **Extension Validation**: Validate FHIR extensions and profiles

### Error Recovery
- **Graceful Degradation**: Continue processing when possible after errors
- **Partial Success**: Handle scenarios where some operations succeed
- **Rollback**: Implement rollback mechanisms for failed operations
- **User Guidance**: Provide guidance on how to fix validation errors

## Command-Level Error Handling

### Console Command Errors
- **Exit Codes**: Return appropriate exit codes for different error types
- **User-Friendly Messages**: Display user-friendly error messages in console
- **Verbose Output**: Provide detailed error information in verbose mode
- **Help Suggestions**: Suggest corrective actions when possible

### Error Categorization
- **User Errors**: Input validation, missing files, invalid arguments
- **System Errors**: Network failures, permission issues, resource constraints
- **Logic Errors**: Programming errors, unexpected conditions
- **External Errors**: Third-party service failures, malformed external data

## Testing Error Conditions

### Error Testing Strategy
- **Exception Testing**: Test that correct exceptions are thrown
- **Error Message Testing**: Verify error messages are helpful and accurate
- **Recovery Testing**: Test error recovery mechanisms
- **Edge Case Testing**: Test boundary conditions and unusual inputs

### Mock Error Conditions
- **Network Failures**: Mock network timeouts and connection errors
- **File System Errors**: Mock file permission and disk space issues
- **Invalid Data**: Test with malformed FHIR data and edge cases
- **Resource Exhaustion**: Test memory and time limit scenarios