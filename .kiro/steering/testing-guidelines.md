---
inclusion: always
---

# Testing Guidelines for PHP FHIRTools

## Test Structure and Organization

### Test Directory Structure
- **Unit Tests**: Place in `tests/` directory mirroring `src/` structure
- **Integration Tests**: Group related integration tests in subdirectories
- **Test Data**: Store test FHIR data in `tests/fixtures/` or similar
- **Mocks**: Create reusable mocks for external dependencies

### PHPUnit Standards
- **Version**: Use PHPUnit 12+ features and syntax
- **Assertions**: Always use `self::assert*()` methods, never `$this->assert*()`
- **Return Types**: Do not use `void` return types on test methods
- **Setup/Teardown**: Use `setUp()` and `tearDown()` methods appropriately
- **Data Providers**: Use data providers for parameterized tests

### Test Naming Conventions
- **Method Names**: Use descriptive names like `testGeneratesFHIRClassFromValidStructureDefinition()`
- **Test Classes**: Follow pattern `{ClassUnderTest}Test.php`
- **Assertions**: Make assertion messages clear and helpful

## Testing Strategies

### Unit Testing
- **Isolation**: Test individual classes in isolation using mocks
- **Coverage**: Aim for high code coverage on core business logic
- **Edge Cases**: Test boundary conditions and error scenarios
- **Mocking**: Mock external dependencies (HTTP clients, file system, etc.)

### Integration Testing
- **End-to-End**: Test complete FHIR generation workflows
- **Real Data**: Use actual FHIR StructureDefinitions for testing
- **Output Validation**: Verify generated code compiles and follows standards
- **Performance**: Include performance tests for large FHIR packages

### Test Data Management
- **Fixtures**: Use consistent test fixtures for FHIR data
- **Builders**: Create test data builders for complex objects
- **Cleanup**: Ensure tests clean up generated files and temporary data
- **Isolation**: Each test should be independent and repeatable

## Specific Testing Areas

### FHIR Generation Testing
- **Valid Input**: Test with valid FHIR StructureDefinitions
- **Invalid Input**: Test error handling with malformed FHIR data
- **Edge Cases**: Test unusual but valid FHIR structures
- **Output Quality**: Verify generated code meets quality standards

### Error Handling Testing
- **Exception Types**: Test that correct exception types are thrown
- **Error Messages**: Verify error messages are helpful and accurate
- **Recovery**: Test error recovery and retry mechanisms
- **Logging**: Verify appropriate logging of errors and warnings

### Command Testing
- **Console Commands**: Test Symfony console commands with various inputs
- **Exit Codes**: Verify correct exit codes for success/failure scenarios
- **Output Format**: Test command output formatting and verbosity levels
- **Help Text**: Verify command help and usage information

## Test Execution
- **Command**: Run tests with `composer run test`
- **Coverage**: Generate coverage reports when needed
- **CI/CD**: Ensure tests pass in continuous integration
- **Performance**: Monitor test execution time and optimize slow tests