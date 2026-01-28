---
inclusion: always
---

# Symfony Console Command Patterns

## Command Structure Standards

### Command Class Organization
- **Namespace**: Place commands in appropriate namespace (e.g., `App\Command`)
- **Naming**: Use descriptive names ending with `Command` (e.g., `FHIRModelGeneratorCommand`)
- **Inheritance**: Extend `Symfony\Component\Console\Command\Command`
- **Attributes**: Use `#[AsCommand]` attribute for command configuration

### Command Configuration
```php
#[AsCommand(
    name: 'fhir:generate',
    description: 'Generate FHIR model classes from StructureDefinitions',
    aliases: ['fhir:gen']
)]
class FHIRModelGeneratorCommand extends Command
```

### Dependency Injection in Commands
- **Constructor Injection**: Inject services through constructor
- **Service Locator**: Avoid service locator pattern
- **Autowiring**: Leverage Symfony's autowiring capabilities
- **Tagged Services**: Use service tags when appropriate

## Input/Output Handling

### Arguments and Options
- **Clear Names**: Use descriptive argument and option names
- **Validation**: Validate inputs early in the command execution
- **Default Values**: Provide sensible defaults for optional parameters
- **Help Text**: Include comprehensive help descriptions

### Output Formatting
- **Progress Bars**: Use progress bars for long-running operations
- **Verbosity Levels**: Respect verbosity levels (-v, -vv, -vvv)
- **Styling**: Use Symfony's output styling for consistent formatting
- **Tables**: Use table helper for tabular data display

### Error Handling
- **Exit Codes**: Return appropriate exit codes (0 for success, non-zero for errors)
- **Error Messages**: Provide clear, actionable error messages
- **Exception Handling**: Catch and handle exceptions gracefully
- **Logging**: Log errors appropriately without exposing sensitive data

## Command Execution Patterns

### Service Integration
```php
public function __construct(
    private readonly FHIRModelGenerator $generator,
    private readonly ErrorCollector $errorCollector,
    private readonly PackageLoader $packageLoader
) {
    parent::__construct();
}
```

### Execution Flow
1. **Validate Input**: Check arguments and options early
2. **Setup Services**: Configure services with command parameters
3. **Execute Logic**: Delegate business logic to services
4. **Handle Results**: Process results and provide feedback
5. **Cleanup**: Perform any necessary cleanup operations

### Progress Reporting
- **Start Progress**: Initialize progress bars for long operations
- **Update Progress**: Regularly update progress during execution
- **Finish Progress**: Complete progress bars with final status
- **Status Messages**: Provide meaningful status updates

## Testing Console Commands

### Command Testing Strategy
- **Functional Tests**: Test complete command execution
- **Input Testing**: Test various input combinations
- **Output Testing**: Verify output format and content
- **Error Testing**: Test error conditions and exit codes

### Test Helpers
- **CommandTester**: Use Symfony's CommandTester for testing
- **Mock Services**: Mock injected services for isolated testing
- **Fixtures**: Use test fixtures for consistent test data
- **Assertions**: Assert on exit codes, output content, and side effects

## Performance Considerations

### Memory Management
- **Large Datasets**: Handle large FHIR packages efficiently
- **Memory Limits**: Monitor and manage memory usage
- **Streaming**: Use streaming for large file operations
- **Garbage Collection**: Explicitly unset large objects when done

### Execution Time
- **Timeouts**: Set appropriate timeouts for network operations
- **Batching**: Process data in batches to avoid timeouts
- **Caching**: Cache expensive operations when possible
- **Optimization**: Profile and optimize slow operations