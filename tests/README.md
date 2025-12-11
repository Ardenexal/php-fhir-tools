# FHIR Tools Testing Framework

This directory contains the comprehensive testing framework for PHP FHIRTools, including unit tests, integration tests, FHIR-specific tests, and property-based testing utilities.

## Test Structure

```
tests/
├── Unit/                    # Unit tests for individual classes
├── Integration/             # Integration tests for complete workflows
├── FHIR/                   # FHIR-specific validation and generation tests
├── Fixtures/               # Test data and FHIR examples
├── Utilities/              # Testing utilities and helpers
└── README.md              # This file
```

## Test Suites

### Unit Tests (`tests/Unit/`)
- Test individual classes in isolation
- Use mocks for external dependencies
- Focus on business logic and edge cases
- Fast execution (< 1 second per test)

### Integration Tests (`tests/Integration/`)
- Test complete workflows end-to-end
- Test service integration and file system operations
- Test command execution and output
- Moderate execution time (< 30 seconds per test)

### FHIR Tests (`tests/FHIR/`)
- Test FHIR-specific functionality
- Validate StructureDefinition processing
- Test generated code quality
- Use official FHIR test cases where possible

## Running Tests

### Basic Test Commands

```bash
# Run all tests
composer run test

# Run specific test suite
composer run test-unit
composer run test-integration
composer run test-fhir

# Run with coverage report
composer run test-coverage

# Run comprehensive test suite with performance monitoring
composer run test-comprehensive

# Run performance tests only
composer run test-performance
```

### Advanced Test Options

```bash
# Run specific test file
php vendor/phpunit/phpunit/phpunit tests/Unit/ErrorCollectorTest.php

# Run with verbose output
php vendor/phpunit/phpunit/phpunit --verbose

# Run with specific filter
php vendor/phpunit/phpunit/phpunit --filter testValidStructureDefinition

# Run comprehensive tests with coverage
php tests/run-comprehensive-tests.php --coverage

# Run comprehensive tests for specific suite
php tests/run-comprehensive-tests.php --suite unit
```

## Testing Utilities

### FHIRTestDataGenerator
Property-based test data generator using Eris for creating valid and invalid FHIR data structures.

```php
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;

// Generate FHIR resource types
$resourceType = FHIRTestDataGenerator::fhirResourceType();

// Generate cardinality strings
$cardinality = FHIRTestDataGenerator::fhirCardinality();

// Generate invalid data for negative testing
$invalidCardinality = FHIRTestDataGenerator::invalidFhirCardinality();
```

### TestCase Base Class
Extended PHPUnit TestCase with FHIR-specific assertion methods and utilities.

```php
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

class MyTest extends TestCase
{
    public function testSomething(): void
    {
        // Use FHIR-specific assertions
        $this->assertErrorCollectorContains($errorCollector, 'Invalid cardinality');
        $this->assertValidPhpCode($generatedCode);
        $this->assertUsesStrictTypes($generatedCode);
    }
}
```

### FHIRTestCaseRepository
Integration with official FHIR test cases and generation of comprehensive test data.

```php
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestCaseRepository;

$repository = new FHIRTestCaseRepository();
$testCases = $repository->getTestCasesForResource('Patient', 'R4B');
$validationCases = $repository->getValidationTestCases();
```

## Property-Based Testing

The framework uses [Eris](https://github.com/giorgiosironi/eris) for property-based testing to automatically generate test cases and find edge cases.

```php
use Eris\TestTrait;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;

class PropertyBasedTest extends TestCase
{
    use TestTrait;

    public function testCardinalityValidation(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirCardinality(),
            FHIRTestDataGenerator::fhirElementPath()
        )->then(function (string $cardinality, string $elementPath): void {
            // Test that valid cardinality doesn't produce errors
            $this->validateCardinality($cardinality, $elementPath);
            self::assertFalse($this->errorCollector->hasErrors());
        });
    }
}
```

## Test Fixtures

### FHIR StructureDefinitions
- `tests/Fixtures/StructureDefinitions/Patient.json` - Valid Patient profile
- `tests/Fixtures/StructureDefinitions/Observation.json` - Valid Observation profile
- `tests/Fixtures/StructureDefinitions/InvalidStructureDefinition.json` - Invalid profile for error testing

### Test Data Categories
- **Valid Examples**: Properly formed FHIR resources and profiles
- **Invalid Examples**: Malformed data for negative testing
- **Edge Cases**: Boundary conditions and unusual but valid data
- **Performance Data**: Large datasets for performance testing

## Coverage Requirements

- **Unit Tests**: Minimum 90% code coverage
- **Integration Tests**: Cover all major workflows
- **FHIR Tests**: Cover all supported FHIR versions and resource types
- **Error Handling**: Test all exception paths and error conditions

## Performance Testing

The framework includes performance monitoring to ensure:
- Memory usage remains reasonable during large operations
- Generation speed meets performance targets
- Large FHIR packages are processed efficiently

```bash
# Run performance tests
composer run test-performance

# Run comprehensive tests with performance monitoring
php tests/run-comprehensive-tests.php --performance
```

## Best Practices

### Test Naming
- Use descriptive test method names: `testGeneratesFHIRClassFromValidStructureDefinition()`
- Group related tests in the same class
- Use data providers for parameterized tests

### Assertions
- Always use `self::assert*()` methods, never `$this->assert*()`
- Provide meaningful assertion messages
- Test both positive and negative cases

### Test Data
- Use fixtures for complex test data
- Generate test data programmatically when possible
- Clean up temporary files and directories

### Error Testing
- Test all exception types and error conditions
- Verify error messages are helpful and actionable
- Test error recovery mechanisms

## Continuous Integration

The testing framework is designed to work with CI/CD pipelines:

```yaml
# Example GitHub Actions workflow
- name: Run Tests
  run: |
    composer run test-comprehensive --coverage
    
- name: Upload Coverage
  uses: codecov/codecov-action@v1
  with:
    file: ./coverage/clover.xml
```

## Contributing

When adding new tests:

1. Follow the existing directory structure
2. Use the appropriate test utilities and base classes
3. Include both positive and negative test cases
4. Add property-based tests for complex logic
5. Update this README if adding new testing patterns

## Troubleshooting

### Common Issues

**Tests running slowly**: Check for network operations in unit tests, use mocks instead.

**Memory issues**: Ensure proper cleanup in `tearDown()` methods, avoid large test data in memory.

**Flaky tests**: Check for timing dependencies, use deterministic test data.

**Coverage issues**: Ensure all code paths are tested, including error conditions.

### Debug Commands

```bash
# Run single test with debug output
php vendor/phpunit/phpunit/phpunit --debug tests/Unit/ErrorCollectorTest.php

# Check test configuration
php vendor/phpunit/phpunit/phpunit --list-suites

# Validate PHPUnit configuration
php vendor/phpunit/phpunit/phpunit --configuration phpunit.dist.xml --list-tests
```