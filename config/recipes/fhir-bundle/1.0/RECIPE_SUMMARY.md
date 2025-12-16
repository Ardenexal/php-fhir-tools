# FHIR Bundle Symfony Flex Recipe Summary

## Overview

This Symfony Flex recipe provides automatic configuration for the FHIR Bundle, enabling seamless integration of FHIR code generation and serialization capabilities into Symfony applications.

## Recipe Structure

```
config/recipes/fhir-bundle/1.0/
├── manifest.json                    # Flex recipe manifest
├── config/
│   └── packages/
│       ├── fhir.yaml               # Main configuration
│       ├── dev/
│       │   └── fhir.yaml           # Development overrides
│       ├── prod/
│       │   └── fhir.yaml           # Production overrides
│       └── test/
│           └── fhir.yaml           # Test overrides
├── README.md                       # Recipe documentation
├── INSTALLATION.md                 # Installation guide
├── RECIPE_SUMMARY.md              # This file
└── post-install.txt               # Post-installation message
```

## What the Recipe Does

### 1. Bundle Registration
- Automatically registers `FHIRBundle` in `config/bundles.php`
- Enables the bundle for all environments (`['all']`)

### 2. Configuration Files
- Creates main configuration in `config/packages/fhir.yaml`
- Sets up environment-specific overrides for dev, prod, and test
- Uses environment variables for flexible configuration

### 3. Environment Variables
Sets up the following environment variables in `.env`:
- `FHIR_OUTPUT_DIRECTORY`: Where generated FHIR classes are stored
- `FHIR_CACHE_DIRECTORY`: Where FHIR packages are cached
- `FHIR_DEFAULT_VERSION`: Default FHIR version (R4, R4B, R5)
- `FHIR_VALIDATION_ENABLED`: Enable/disable FHIR validation
- `FHIR_VALIDATION_STRICT_MODE`: Enable/disable strict validation

### 4. Gitignore Entries
Adds appropriate `.gitignore` entries:
- `/output/` - Generated FHIR classes
- `/var/cache/fhir/` - FHIR package cache

### 5. Package Configuration
Pre-configures common FHIR packages:
- `hl7.fhir.r4.core` - FHIR R4 core specification
- `hl7.fhir.r4b.core` - FHIR R4B core specification
- `hl7.fhir.r5.core` - FHIR R5 core specification
- `hl7.terminology` - HL7 terminology resources

## Environment-Specific Configuration

### Development Environment
- Strict validation disabled for faster iteration
- Auto-update enabled for packages
- Relaxed validation settings

### Production Environment
- Strict validation enabled for data integrity
- Auto-update disabled for stability
- Enhanced validation settings

### Test Environment
- Separate output and cache directories
- Validation enabled but not strict
- Fixed package versions for reproducible tests

## Services Provided

The recipe enables access to the following services:

### Core Services
- `FHIRModelGenerator` - Generate FHIR model classes
- `FHIRValueSetGenerator` - Generate FHIR value sets
- `PackageLoader` - Load and manage FHIR packages
- `ErrorCollector` - Collect and manage errors
- `RetryHandler` - Handle retry logic for network operations

### Serialization Services
- `FHIRSerializationService` - Main serialization service
- `FHIRValidator` - Validate FHIR resources
- `FHIRSchemaValidator` - Schema-based validation
- `FHIRTypeResolver` - Resolve FHIR types
- `FHIRMetadataExtractor` - Extract FHIR metadata

### Normalizers
- `FHIRResourceNormalizer` - Normalize FHIR resources
- `FHIRComplexTypeNormalizer` - Normalize complex types
- `FHIRPrimitiveTypeNormalizer` - Normalize primitive types
- `FHIRBackboneElementNormalizer` - Normalize backbone elements

### Service Aliases
- `fhir.model_generator` → `FHIRModelGenerator`
- `fhir.serialization_service` → `FHIRSerializationService`
- `fhir.package_loader` → `PackageLoader`
- `fhir.validator` → `FHIRValidator`

## Console Commands

The recipe enables the following console commands:
- `fhir:generate` - Generate FHIR model classes from StructureDefinitions

## Installation Methods

### Automatic (Recommended)
```bash
composer require ardenexal/fhir-bundle
```

### Manual
```bash
composer require ardenexal/fhir-bundle --no-recipes
# Then manually copy configuration files and register bundle
```

## Usage Examples

### Generate FHIR Classes
```bash
php bin/console fhir:generate R4B
```

### Use in Controllers
```php
public function __construct(
    private FHIRSerializationService $fhirSerializer,
    private FHIRModelGenerator $generator
) {}
```

### Access via Container
```php
$generator = $this->container->get('fhir.model_generator');
```

## Customization

The recipe provides a solid foundation that can be customized:
- Add custom FHIR packages
- Modify output directory structure
- Adjust validation settings
- Configure environment-specific behavior

## Validation

The recipe includes comprehensive validation:
- Manifest structure validation
- Configuration file validation
- YAML syntax validation
- Required key validation
- Service registration validation

## Testing

The recipe includes integration tests:
- `FlexRecipeTest` - Validates recipe structure
- `RecipeInstallationTest` - Simulates installation process

## Requirements

- PHP 8.2+
- Symfony 6.4+ or 7.0+
- Composer with Symfony Flex

## Support

- Repository: https://github.com/ardenexal/fhir-tools
- Issues: https://github.com/ardenexal/fhir-tools/issues
- Documentation: See README.md and INSTALLATION.md