# Error Handling Patterns

## Exception Hierarchy

### Core Exceptions (src/Exception/)
- **FHIRToolsException**: Base exception for project
- **GenerationException**: FHIR code generation errors
- **ValidationException**: FHIR validation errors
- **PackageException**: FHIR package loading errors

### Component-Specific Exceptions

#### CodeGeneration (src/Component/CodeGeneration/src/Exception/)
- `GenerationException`
- `PackageException`

#### Serialization (src/Component/Serialization/src/Exception/)
- Serialization-specific exceptions

#### FHIRPath (src/Component/FHIRPath/src/Exception/)
- `FHIRPathException`
- `ParseException`
- `EvaluationException`
- `TokenException`
- `SyntaxException`

**Note**: FHIRPath exceptions extend `RuntimeException` rather than `FHIRToolsException`. This should be refactored for consistency with other components.

## ErrorCollector Pattern

The `ErrorCollector` class accumulates validation errors during generation:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;

$errorCollector = new ErrorCollector();

// Add errors during processing
$errorCollector->addError('Invalid cardinality in Patient.name');
$errorCollector->addWarning('Deprecated element usage');

// Check for errors
if ($errorCollector->hasErrors()) {
    foreach ($errorCollector->getErrors() as $error) {
        // Handle error
    }
}
```

## Error Message Standards

Provide specific, actionable error messages:
```php
// Good: Specific and actionable
throw new ValidationException(
    'Invalid cardinality "2..0" in element "Patient.name": minimum cannot exceed maximum'
);

// Bad: Vague and unhelpful
throw new \Exception('Validation failed');
```

## RetryHandler for Network Operations

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\RetryHandler;

$retryHandler = new RetryHandler();
$result = $retryHandler->execute(
    fn() => $this->downloadPackage($url),
    maxAttempts: 3
);
```

## Console Command Error Handling

### Exit Codes
- `0`: Success
- `1`: General error
- Use `Command::FAILURE` and `Command::SUCCESS` constants

### User-Friendly Messages
- Display clear error messages in console output
- Provide detailed information with `-vvv` verbose mode
- Suggest corrective actions when possible

## Logging Guidelines
- Use appropriate log levels (ERROR, WARNING, INFO, DEBUG)
- Never log sensitive information
- Include relevant context in log entries
