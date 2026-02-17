# FHIR Bundle

Symfony Bundle for integrating FHIR Tools components into Symfony applications. Provides automatic service registration, console commands, and semantic configuration.

## Features

- Automatic service registration in the Symfony container
- Console commands for FHIR generation and FHIRPath evaluation
- Semantic configuration through YAML
- Symfony Flex recipe for automatic setup

## Installation

This bundle is part of the [PHP FHIR Tools](../../README.md) monorepo. It is registered automatically when the project is set up.

### Manual Registration

If needed, register the bundle in `config/bundles.php`:

```php
return [
    // ...
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

## Configuration

### Basic Configuration

```yaml
# config/packages/fhir.yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\\FHIR'
    serialization:
        strict_validation: true
    path:
        cache_size: 100
```

### Environment Variables

```bash
FHIR_OUTPUT_DIR=/path/to/output
FHIR_BASE_NAMESPACE=MyApp\\FHIR
FHIR_DEBUG=false
```

## Registered Services

| Service ID | Class | Description |
|------------|-------|-------------|
| `fhir.model_generator` | `FHIRModelGenerator` | Generates FHIR model classes |
| `fhir.valueset_generator` | `FHIRValueSetGenerator` | Generates FHIR value set enums |
| `fhir.serialization_service` | `FHIRSerializationService` | FHIR serialization and deserialization |
| `fhir.package_loader` | `PackageLoader` | Loads FHIR packages from registries |
| `fhir.builder_context` | `BuilderContext` | Code generation context |
| `fhir.path_service` | `FHIRPathService` | Evaluates FHIRPath expressions |

### Using Services

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

class FHIRController extends AbstractController
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
        private readonly FHIRPathService $pathService,
    ) {}

    public function serializePatient(object $patient): JsonResponse
    {
        $json = $this->serializer->serializeToJson($patient);

        return new JsonResponse($json, json: true);
    }

    public function queryPatient(object $patient): JsonResponse
    {
        $result = $this->pathService->evaluate('name.given', $patient);

        return new JsonResponse($result->toArray());
    }
}
```

## Console Commands

### Generate FHIR Models

```bash
# Generate models from a specific FHIR package
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv

# Generate using only cached packages (no network)
php bin/console fhir:generate --package=hl7.fhir.r4.core --offline -vvv
```

### FHIRPath Commands

```bash
# Evaluate FHIRPath expression
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Output formats
php bin/console fhir:path:evaluate "name.given" patient.json --format=json --pretty
php bin/console fhir:path:evaluate "name.given" patient.json --format=count

# Show cache statistics
php bin/console fhir:path:evaluate "name" patient.json -v

# Validate FHIRPath expression syntax
php bin/console fhir:path:validate "name.where(use = 'official').given.first()"
```

## Requirements

- **PHP**: 8.3 or higher
- **Symfony**: 6.4+

## License

This bundle is released under the MIT License. See the [LICENSE](../../LICENSE) file for details.
