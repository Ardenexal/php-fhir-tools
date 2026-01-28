# Testing Guidelines for PHP FHIRTools

## Test Structure

### Directory Organization
- **Tests**: Located in `tests/` directory
- **Unit Tests**: `tests/Unit/` mirroring `src/` structure
- **Integration Tests**: `tests/Integration/`
- **Fixtures**: `tests/Fixtures/FHIR/` for FHIR test data
- **Base TestCase**: `tests/TestCase.php` provides reusable utilities

### PHPUnit Standards
- **Version**: PHPUnit 11+/12+
- **Assertions**: Always use `self::assert*()` methods, never `$this->assert*()`
- **Return Types**: Use `void` return types on test methods
- **Data Providers**: Use data providers for parameterized tests
- **Setup/Teardown**: Use `setUp()` and `tearDown()` methods appropriately

### Test Naming Conventions
- **Method Names**: Use descriptive names like `testGeneratesFHIRClassFromValidStructureDefinition()`
- **Test Classes**: Follow pattern `{ClassUnderTest}Test.php`

## Running Tests

### Available Commands
```bash
# Run all tests
composer test

# Run specific test suites
composer test-unit
composer test-integration
composer test-fhir

# Component-specific tests
composer test:bundle
composer test:codegen
composer test:serialization

# Full quality check (lint + phpstan + test)
composer quality:all
```

## Testing Strategies

### Unit Testing
- Test individual classes in isolation using mocks
- Mock external dependencies (HTTP clients, file system)
- Test boundary conditions and error scenarios

### Integration Testing
- Test complete FHIR generation workflows
- Use actual FHIR StructureDefinitions from `tests/Fixtures/`
- Verify generated code compiles and follows standards
- Test serialization round-trips

### Command Testing
- Use Symfony's `CommandTester` for console command testing
- Test with various input combinations
- Verify correct exit codes (0 for success, non-zero for errors)
- Test output formatting and verbosity levels

## Test Data Management
- Use consistent test fixtures from `tests/Fixtures/FHIR/`
- Each test should be independent and repeatable
- Clean up generated files and temporary data after tests
