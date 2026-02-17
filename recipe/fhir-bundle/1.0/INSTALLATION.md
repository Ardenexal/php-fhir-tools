# FHIR Bundle Installation Guide

This guide explains how to install and configure the FHIR Bundle using Symfony Flex.

## Automatic Installation (Recommended)

The easiest way to install the FHIR Bundle is using Symfony Flex:

```bash
composer require ardenexal/fhir-bundle
```

This will automatically:
- Register the bundle in `config/bundles.php`
- Copy configuration files to `config/packages/`
- Set up environment variables in `.env`
- Add appropriate `.gitignore` entries

## Manual Installation

If you prefer manual installation or need to customize the process:

### 1. Install the Package

```bash
composer require ardenexal/fhir-bundle --no-recipes
```

### 2. Register the Bundle

Add the bundle to `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all'],
];
```

### 3. Copy Configuration Files

Copy the configuration files from this recipe to your project:

- `config/packages/fhir.yaml` - Main configuration
- `config/packages/dev/fhir.yaml` - Development overrides
- `config/packages/prod/fhir.yaml` - Production overrides  
- `config/packages/test/fhir.yaml` - Test overrides

### 4. Set Environment Variables

Add these variables to your `.env` file:

```env
FHIR_OUTPUT_DIRECTORY=%kernel.project_dir%/output
FHIR_CACHE_DIRECTORY=%kernel.cache_dir%/fhir
FHIR_DEFAULT_VERSION=R4B
FHIR_VALIDATION_ENABLED=true
FHIR_VALIDATION_STRICT_MODE=false
```

### 5. Update .gitignore

Add these entries to your `.gitignore`:

```
/output/
/var/cache/fhir/
```

## Configuration

### Basic Configuration

The main configuration is in `config/packages/fhir.yaml`:

```yaml
fhir:
    output_directory: '%env(FHIR_OUTPUT_DIRECTORY)%'
    cache_directory: '%env(FHIR_CACHE_DIRECTORY)%'
    default_version: '%env(FHIR_DEFAULT_VERSION)%'
    validation:
        enabled: '%env(bool:FHIR_VALIDATION_ENABLED)%'
        strict_mode: '%env(bool:FHIR_VALIDATION_STRICT_MODE)%'
    packages:
        'hl7.fhir.r4b.core':
            version: '4.3.0'
            auto_update: false
```

### Environment-Specific Configuration

#### Development (`config/packages/dev/fhir.yaml`)
- Strict validation disabled for faster iteration
- Auto-update enabled for packages
- Relaxed validation settings

#### Production (`config/packages/prod/fhir.yaml`)
- Strict validation enabled
- Auto-update disabled for stability
- Enhanced validation settings

#### Test (`config/packages/test/fhir.yaml`)
- Separate output and cache directories
- Validation enabled but not strict
- Fixed package versions for reproducible tests

## Usage

### Generate FHIR Classes

```bash
# Generate classes for default version (R4B)
php bin/console fhir:generate

# Generate classes for specific version
php bin/console fhir:generate R5

# Generate with custom output directory
php bin/console fhir:generate R4B --output-dir=/custom/path
```

### Use FHIR Services

Inject FHIR services into your controllers or services:

```php
use Ardenexal\FHIRTools\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\FHIRModelGenerator;

class MyController
{
    public function __construct(
        private FHIRSerializationService $fhirSerializer,
        private FHIRModelGenerator $fhirGenerator
    ) {}
    
    public function generateModels(): Response
    {
        $this->fhirGenerator->generate('R4B');
        return new Response('Models generated successfully');
    }
}
```

### Access Services by Alias

You can also access services using their aliases:

```php
// In a controller
$generator = $this->container->get('fhir.model_generator');
$serializer = $this->container->get('fhir.serialization_service');
$validator = $this->container->get('fhir.validator');
```

## Customization

### Adding Custom FHIR Packages

Add custom packages to your configuration:

```yaml
fhir:
    packages:
        'my.custom.package':
            version: '1.0.0'
            url: 'https://example.com/my-package.tgz'
            auto_update: false
```

### Custom Output Directory Structure

Customize the output directory structure:

```yaml
fhir:
    output_directory: '%kernel.project_dir%/src/FHIR/Generated'
```

### Validation Settings

Configure validation behavior:

```yaml
fhir:
    validation:
        enabled: true
        strict_mode: true  # Fail on warnings
```

## Troubleshooting

### Common Issues

1. **Permission Errors**: Ensure the output and cache directories are writable
2. **Memory Issues**: Increase PHP memory limit for large FHIR packages
3. **Network Issues**: Check firewall settings for FHIR package downloads

### Debug Mode

Enable debug mode for more verbose output:

```bash
php bin/console fhir:generate --verbose
```

### Clear Cache

If you encounter issues, try clearing the FHIR cache:

```bash
rm -rf var/cache/fhir/
```

## Support

- Documentation: https://github.com/ardenexal/fhir-tools
- Issues: https://github.com/ardenexal/fhir-tools/issues
- FHIR Specification: https://hl7.org/fhir/