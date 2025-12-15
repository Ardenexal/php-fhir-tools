# FHIR Bundle

Symfony Bundle for integrating FHIR Tools components into Symfony applications.

## Installation

```bash
composer require ardenexal/fhir-bundle
```

The bundle will be automatically registered via Symfony Flex.

## Configuration

Configure the bundle in `config/packages/fhir.yaml`:

```yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\FHIR'
        generate_tests: false
    serialization:
        strict_validation: true
        cache_metadata: true
```

## Services

The bundle provides the following services:

- `fhir.model_generator`: FHIR model generation service
- `fhir.serialization_service`: FHIR serialization service

## Requirements

- PHP 8.3+
- Symfony 6.4+ or 7.4+
- ardenexal/fhir-code-generation
- ardenexal/fhir-serialization