# FHIR Bundle Symfony Flex Recipe

This recipe automatically configures the FHIR Bundle for Symfony applications.

## What it does

- Registers the `FHIRBundle` in your Symfony application
- Creates configuration files in `config/packages/fhir.yaml`
- Sets up environment variables for FHIR configuration
- Adds appropriate `.gitignore` entries for generated files and cache
- Provides environment-specific configuration for dev, prod, and test

## Configuration

The recipe creates the following configuration structure:

```
config/
├── packages/
│   ├── fhir.yaml              # Main configuration
│   ├── dev/
│   │   └── fhir.yaml          # Development overrides
│   ├── prod/
│   │   └── fhir.yaml          # Production overrides
│   └── test/
│       └── fhir.yaml          # Test overrides
```

## Environment Variables

The following environment variables are available for configuration:

- `FHIR_OUTPUT_DIRECTORY`: Directory where generated FHIR classes are stored
- `FHIR_CACHE_DIRECTORY`: Directory for FHIR package cache
- `FHIR_DEFAULT_VERSION`: Default FHIR version (R4, R4B, R5)
- `FHIR_VALIDATION_ENABLED`: Enable/disable FHIR validation
- `FHIR_VALIDATION_STRICT_MODE`: Enable/disable strict validation mode

## Usage

After installation, you can:

1. Generate FHIR classes:
   ```bash
   php bin/console fhir:generate R4B
   ```

2. Use FHIR services in your application:
   ```php
   use Ardenexal\FHIRTools\Serialization\FHIRSerializationService;
   
   public function __construct(
       private FHIRSerializationService $fhirSerializer
   ) {}
   ```

3. Customize configuration in `config/packages/fhir.yaml`

## Package Configuration

The recipe includes configuration for common FHIR packages:

- `hl7.fhir.r4.core`: FHIR R4 core specification
- `hl7.fhir.r4b.core`: FHIR R4B core specification  
- `hl7.fhir.r5.core`: FHIR R5 core specification
- `hl7.terminology`: HL7 terminology resources

You can add additional packages or modify versions in the configuration file.

## Environment-Specific Settings

### Development
- Strict validation disabled for faster iteration
- Auto-update enabled for packages
- Relaxed validation settings

### Production
- Strict validation enabled
- Auto-update disabled for stability
- Enhanced validation settings

### Test
- Separate output and cache directories
- Validation enabled but not strict
- Fixed package versions for reproducible tests