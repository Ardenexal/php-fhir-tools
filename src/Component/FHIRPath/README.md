# FHIRPath Component

PHP implementation of the [FHIRPath 2.0 specification](http://hl7.org/fhirpath/N1/) for evaluating path expressions against FHIR resources.

## Features

- FHIRPath 2.0 compliant expression evaluation
- 50+ built-in functions (existence, filtering, string, math, date/time, type conversion)
- 20+ operators with correct precedence
- FHIR-aligned type system with `is`/`as` operators
- Expression caching with LRU eviction
- Pre-compilation for repeated evaluation

## Quick Start

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService();

// Evaluate against FHIR data
$result = $service->evaluate('Patient.name.given', $patient);

// Filtering
$result = $service->evaluate('name.where(use = "official").given.first()', $patient);

// Boolean check
$hasPhone = $service->evaluate('telecom.where(system = "phone").exists()', $patient);
```

### Compilation and Caching

```php
// Compile once, evaluate many times
$compiled = $service->compile('name.where(use = "official").given.first()');

foreach ($patients as $patient) {
    $result = $compiled->evaluate($patient);
}

// Cache statistics
$stats = $service->getCacheStats();
// ['hits' => 10, 'misses' => 2, 'size' => 5]
```

### Validation

```php
$isValid = $service->validate('name.given');        // true
$isValid = $service->validate('name.given.???');     // false
```

## Supported Operations

### Functions

```php
// Existence
$service->evaluate('name.exists()', $patient);
$service->evaluate('name.empty()', $patient);
$service->evaluate('telecom.all(system = "phone")', $patient);

// Filtering and projection
$service->evaluate('name.where(use = "official")', $patient);
$service->evaluate('name.select(given)', $patient);
$service->evaluate('name.first()', $patient);

// String
$service->evaluate('name.given.upper()', $patient);
$service->evaluate('name.family.substring(0, 3)', $patient);
$service->evaluate('name.given.matches("[A-Z].*")', $patient);

// Math
$service->evaluate('(42).abs()', null);
$service->evaluate('Observation.value.sum()', $bundle);

// Type
$service->evaluate('value.toInteger()', $observation);
$service->evaluate('value is Quantity', $observation);
```

### Operators

```php
// Comparison
$service->evaluate('age > 18', $patient);
$service->evaluate('status = "active"', $patient);

// Logical
$service->evaluate('age > 18 and status = "active"', $patient);
$service->evaluate('value > 100 or value < 0', $observation);

// Arithmetic
$service->evaluate('value * 2', $observation);
$service->evaluate('(value + 10) / 2', $observation);

// Union
$service->evaluate('name | telecom', $patient);
```

## Error Handling

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;

try {
    $result = $service->evaluate('invalid..path', $patient);
} catch (FHIRPathException $e) {
    echo "FHIRPath error: {$e->getMessage()}";
}
```

## Console Commands

When used with the FHIRBundle:

```bash
# Evaluate expression
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Validate syntax
php bin/console fhir:path:validate "name.where(use = 'official').given.first()"
```

## Requirements

- **PHP**: 8.3 or higher

## Testing

This component includes comprehensive test coverage:

### Unit Tests
```bash
composer test:fhir-path
```

### Specification Conformance Tests

The component includes official FHIR FHIRPath specification conformance tests that validate compliance against the FHIR specification test cases:

```bash
# Run FHIRPath specification tests
composer test-fhirpath-spec
```

**Note**: These tests are currently in development as the FHIRPath evaluator implementation is being completed. The tests automatically load test cases from the official `fhir/fhir-test-cases` package and track implementation progress.

The specification tests are maintained in a separate test suite to:
- Allow focused development on specific FHIRPath features
- Track progress toward full specification compliance  
- Prevent blocking other integration tests during development

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
