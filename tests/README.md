# FHIR Tools Test Suite

## Structure

```
tests/
├── Unit/                    # Unit tests for individual classes
├── Integration/             # Integration tests for complete workflows
├── FHIR/                   # FHIR-specific validation and generation tests
├── Fixtures/               # Test data and FHIR examples
├── Utilities/              # Testing utilities and base classes
└── README.md
```

## Running Tests

```bash
# Run all tests
composer test

# Run specific suites
composer test-unit
composer test-integration
composer test-fhir

# Component-specific tests
composer test:bundle
composer test:codegen
composer test:serialization
composer test:fhir-path

# With coverage report
composer test-coverage
```

## Test Utilities

### TestCase Base Class

Extended PHPUnit TestCase with FHIR-specific assertions:

```php
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

class MyTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertValidPhpCode($generatedCode);
        $this->assertUsesStrictTypes($generatedCode);
        $this->assertErrorCollectorContains($errorCollector, 'Invalid cardinality');
    }
}
```

### FHIRTestDataGenerator

Property-based test data generation using [Eris](https://github.com/giorgiosironi/eris):

```php
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;

$resourceType = FHIRTestDataGenerator::fhirResourceType();
$cardinality = FHIRTestDataGenerator::fhirCardinality();
```

## Conventions

- Use `self::assert*()` methods (not `$this->assert*()`)
- All test methods must have `void` return types
- Clean up temporary files in `tearDown()`
- Use fixtures from `tests/Fixtures/` for complex test data
