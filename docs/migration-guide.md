# Migration Guide: Monolithic to Multi-Project Structure

## Overview

This guide provides step-by-step instructions for migrating from the monolithic PHP FHIRTools structure to the new multi-project format with separate Bundle, CodeGeneration, and Serialization components.

## Migration Timeline

The migration is designed to be **non-breaking** and can be done incrementally:

1. **Phase 1**: Install new structure alongside existing code
2. **Phase 2**: Update imports to use new namespaces (optional)
3. **Phase 3**: Remove legacy aliases when ready (optional)

## Before You Start

### Prerequisites

- PHP 8.2 or higher
- Composer 2.0 or higher
- Symfony 6.4 or 7.4 (if using the bundle)

### Backup Your Code

```bash
# Create a backup of your current installation
cp -r /path/to/php-fhir-tools /path/to/php-fhir-tools-backup
```

## Phase 1: Install New Structure

### Step 1: Update Root Package

The root package now acts as a meta-package that includes all components:

```bash
# Update to the latest version
composer update ardenexal/fhir-tools
```

### Step 2: Verify Installation

Check that all components are properly installed:

```bash
# List installed packages
composer show | grep ardenexal

# Expected output:
# ardenexal/fhir-bundle
# ardenexal/fhir-code-generation  
# ardenexal/fhir-serialization
# ardenexal/fhir-tools
```

### Step 3: Test Existing Code

Your existing code should continue to work without changes:

```bash
# Run your existing tests
composer run test

# Generate FHIR models (existing command)
php bin/console fhir:generate R4B
```

## Phase 2: Update to New Namespaces (Optional)

### Understanding Namespace Changes

| Old Namespace | New Namespace |
|---------------|---------------|
| `Ardenexal\FHIRTools\FHIRModelGenerator` | `Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator` |
| `Ardenexal\FHIRTools\FHIRValueSetGenerator` | `Ardenexal\FHIRTools\Component\CodeGeneration\FHIRValueSetGenerator` |
| `Ardenexal\FHIRTools\Serialization\*` | `Ardenexal\FHIRTools\Component\Serialization\*` |
| `Ardenexal\FHIRTools\Package\*` | `Ardenexal\FHIRTools\Component\CodeGeneration\Package\*` |

### Step 1: Update Import Statements

#### Code Generation Classes

```php
// OLD
use Ardenexal\FHIRTools\FHIRModelGenerator;
use Ardenexal\FHIRTools\FHIRValueSetGenerator;
use Ardenexal\FHIRTools\BuilderContext;

// NEW
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRValueSetGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
```

#### Serialization Classes

```php
// OLD
use Ardenexal\FHIRTools\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;

// NEW
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;
```

#### Package Management Classes

```php
// OLD
use Ardenexal\FHIRTools\Package\PackageLoader;
use Ardenexal\FHIRTools\Package\PackageMetadata;

// NEW
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageMetadata;
```

### Step 2: Update Service Configuration

#### Symfony Services (if using FHIRBundle)

```yaml
# OLD - config/services.yaml
services:
    Ardenexal\FHIRTools\FHIRModelGenerator:
        public: true

# NEW - Automatically configured by FHIRBundle
# No manual configuration needed
```

#### Manual Service Instantiation

```php
// OLD
$generator = new \Ardenexal\FHIRTools\FHIRModelGenerator($context);

// NEW
$generator = new \Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator($context);

// OR (recommended) - Use dependency injection
public function __construct(
    private readonly FHIRModelGenerator $generator
) {}
```

### Step 3: Update Configuration

#### Bundle Configuration

If you're using Symfony, configure the FHIRBundle:

```yaml
# config/packages/fhir.yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\\FHIR'
        generate_tests: false
    serialization:
        strict_validation: true
        cache_metadata: true
```

#### Standalone Usage

For standalone usage, instantiate components directly:

```php
// Code Generation
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$generator = new FHIRModelGenerator($context);

// Serialization
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

$serializer = new FHIRSerializationService();
```

## Phase 3: Remove Legacy Support (Optional)

### When to Remove Legacy Support

Consider removing legacy support when:

- All your code uses new namespaces
- All team members are familiar with new structure
- You want to reduce package size
- You're ready for a major version bump

### Step 1: Update Composer Dependencies

```json
{
    "require": {
        "ardenexal/fhir-bundle": "^2.0",
        "ardenexal/fhir-code-generation": "^2.0",
        "ardenexal/fhir-serialization": "^2.0"
    }
}
```

### Step 2: Remove Legacy Aliases

The new major versions will not include backward compatibility aliases.

## Symfony Integration

### Installing FHIRBundle

#### Via Composer (Recommended)

```bash
composer require ardenexal/fhir-bundle
```

The Symfony Flex recipe will automatically:
- Register the bundle in `config/bundles.php`
- Create default configuration in `config/packages/fhir.yaml`
- Set up directory structure

#### Manual Installation

If Flex is not available:

```php
// config/bundles.php
return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

```yaml
# config/packages/fhir.yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\\FHIR'
```

### Using FHIR Services

#### In Controllers

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

class FHIRController extends AbstractController
{
    public function __construct(
        private readonly FHIRModelGenerator $generator,
        private readonly FHIRSerializationService $serializer
    ) {}

    public function generateModels(): Response
    {
        $this->generator->generate('R4B');
        return new Response('Models generated successfully');
    }
}
```

#### In Commands

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Symfony\Component\Console\Command\Command;

class GenerateModelsCommand extends Command
{
    public function __construct(
        private readonly FHIRModelGenerator $generator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->generator->generate('R4B');
        return Command::SUCCESS;
    }
}
```

## Standalone Usage

### Code Generation Only

```bash
composer require ardenexal/fhir-code-generation
```

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');

$generator = new FHIRModelGenerator($context);
$generator->generate('R4B');
```

### Serialization Only

```bash
composer require ardenexal/fhir-serialization
```

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

$serializer = new FHIRSerializationService();

// Serialize FHIR resource to JSON
$json = $serializer->serialize($fhirResource, 'json');

// Deserialize JSON to FHIR resource
$resource = $serializer->deserialize($json, FHIRPatient::class, 'json');
```

## Testing Your Migration

### Automated Testing

```bash
# Run all tests
composer run test

# Run component-specific tests
composer run test -- tests/Unit/Component/CodeGeneration/
composer run test -- tests/Unit/Component/Serialization/
composer run test -- tests/Unit/Bundle/FHIRBundle/
```

### Manual Testing

#### Test Code Generation

```bash
# Generate FHIR models
php bin/console fhir:generate R4B

# Verify generated files
ls -la src/FHIR/
```

#### Test Serialization

```php
// Test serialization round-trip
$patient = new FHIRPatient();
$patient->setId('test-123');

$json = $serializer->serialize($patient, 'json');
$deserialized = $serializer->deserialize($json, FHIRPatient::class, 'json');

assert($patient->getId() === $deserialized->getId());
```

## Troubleshooting

### Common Issues

#### Class Not Found Errors

```
Error: Class 'Ardenexal\FHIRTools\FHIRModelGenerator' not found
```

**Solution**: Update your import statements to use new namespaces or ensure backward compatibility aliases are loaded.

#### Service Not Found in Container

```
Error: Service 'fhir.model_generator' not found
```

**Solution**: Ensure FHIRBundle is properly registered and configured.

#### Composer Dependency Conflicts

```
Error: Package requirements could not be resolved
```

**Solution**: Update all FHIR-related packages to compatible versions.

### Getting Help

#### Check Configuration

```bash
# Debug Symfony services
php bin/console debug:container fhir

# Check bundle configuration
php bin/console config:dump fhir
```

#### Enable Debug Mode

```yaml
# config/packages/dev/fhir.yaml
fhir:
    debug: true
```

#### Community Support

- **GitHub Issues**: Report bugs and ask questions
- **Documentation**: Check component-specific README files
- **Examples**: See `docs/component-guides/` for detailed examples

## Performance Considerations

### Autoloading Optimization

```bash
# Optimize autoloader after migration
composer dump-autoload --optimize
```

### Component Loading

The new architecture loads components only when needed:

- **CodeGeneration**: Loaded only during generation commands
- **Serialization**: Loaded only when serializing/deserializing
- **Bundle**: Loaded only in Symfony applications

### Memory Usage

Monitor memory usage during migration:

```php
// Before operation
$memoryBefore = memory_get_usage();

// Perform operation
$generator->generate('R4B');

// After operation
$memoryAfter = memory_get_usage();
echo "Memory used: " . ($memoryAfter - $memoryBefore) . " bytes\n";
```

## Best Practices

### Development Workflow

1. **Use Dependency Injection**: Prefer DI over manual instantiation
2. **Component Isolation**: Keep components independent
3. **Interface Programming**: Program against interfaces, not implementations
4. **Testing**: Test components independently and together

### Production Deployment

1. **Lock Dependencies**: Use `composer.lock` for reproducible builds
2. **Optimize Autoloader**: Run `composer dump-autoload --optimize`
3. **Cache Configuration**: Enable Symfony configuration caching
4. **Monitor Performance**: Track component loading and memory usage

### Security

1. **Update Dependencies**: Keep all components updated
2. **Audit Packages**: Regularly audit for security vulnerabilities
3. **Validate Inputs**: Always validate FHIR data inputs
4. **Secure Configuration**: Protect sensitive configuration values

## Conclusion

The migration to multi-project structure provides:

- **Better Modularity**: Use only the components you need
- **Independent Versioning**: Update components independently
- **Improved Testing**: Test components in isolation
- **Enhanced Maintainability**: Clearer separation of concerns

The migration is designed to be seamless with full backward compatibility. Take your time and migrate at your own pace.