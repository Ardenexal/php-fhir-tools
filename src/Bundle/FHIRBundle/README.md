# FHIR Bundle

Symfony Bundle for integrating FHIR Tools components into Symfony applications. This bundle provides seamless integration of FHIR code generation and serialization capabilities into Symfony projects.

## Features

- **Automatic Service Registration**: All FHIR services are automatically registered in the Symfony container
- **Semantic Configuration**: Easy configuration through YAML files
- **Symfony Flex Recipe**: Automatic setup when installed via Composer
- **Cross-Version Compatibility**: Supports Symfony 6.4 and 7.4
- **Development Tools**: Built-in console commands for FHIR operations

## Installation

### Via Composer (Recommended)

```bash
composer require ardenexal/fhir-bundle
```

The bundle will be automatically registered via Symfony Flex, including:
- Bundle registration in `config/bundles.php`
- Default configuration in `config/packages/fhir.yaml`
- Directory structure setup

### Manual Installation

If Symfony Flex is not available:

1. Register the bundle in `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

2. Create configuration file `config/packages/fhir.yaml`:

```yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\\FHIR'
    serialization:
        strict_validation: true
```

## Configuration

### Full Configuration Reference

```yaml
fhir:
    # Code Generation Settings
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'  # Where to generate FHIR classes
        base_namespace: 'App\\FHIR'                        # Base namespace for generated classes
        generate_tests: false                              # Whether to generate test files
        
    # Serialization Settings
    serialization:
        strict_validation: true                            # Enable strict FHIR validation
        cache_metadata: true                               # Cache metadata for performance
        debug: false                                       # Enable debug mode
        
    # FHIRPath Settings
    path:
        cache_size: 100                                    # Maximum cached expressions (10-10000)
```

### Environment Variables

```bash
# .env
FHIR_OUTPUT_DIR=/path/to/output
FHIR_BASE_NAMESPACE=MyApp\\FHIR
FHIR_DEBUG=false
```

```yaml
# config/packages/fhir.yaml
fhir:
    generation:
        output_directory: '%env(FHIR_OUTPUT_DIR)%'
        base_namespace: '%env(FHIR_BASE_NAMESPACE)%'
    serialization:
        debug: '%env(bool:FHIR_DEBUG)%'
```

## Services

The bundle automatically registers the following services:

| Service ID | Class | Description |
|------------|-------|-------------|
| `fhir.model_generator` | `FHIRModelGenerator` | Generates FHIR model classes |
| `fhir.valueset_generator` | `FHIRValueSetGenerator` | Generates FHIR value set enums |
| `fhir.serialization_service` | `FHIRSerializationService` | FHIR serialization and deserialization |
| `fhir.package_loader` | `PackageLoader` | Loads FHIR packages |
| `fhir.builder_context` | `BuilderContext` | Code generation context |
| `fhir.path_service` | `FHIRPathService` | Evaluates FHIRPath expressions |

### Using Services in Your Code

#### In Controllers

```php
<?php

namespace App\Controller;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FHIRController extends AbstractController
{
    public function __construct(
        private readonly FHIRModelGenerator $generator,
        private readonly FHIRSerializationService $serializer,
        private readonly FHIRPathService $pathService
    ) {}

    public function generateModels(): Response
    {
        $this->generator->generate('R4B');
        return new Response('FHIR models generated successfully');
    }

    public function queryPatient($patient): Response
    {
        // Evaluate FHIRPath expression
        $names = $this->pathService->evaluate('name.given', $patient);
        return new JsonResponse($names->toArray());
    }
}
```

#### In Services

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

class PatientService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    public function processPatientJson(string $json): FHIRPatient
    {
        return $this->serializer->deserialize($json, FHIRPatient::class, 'json');
    }
}
```

## Console Commands

The bundle provides several console commands:

### Generate FHIR Models

```bash
# Generate models for FHIR R4B
php bin/console fhir:generate R4B

# Generate with verbose output
php bin/console fhir:generate R4B -v
```

### Validate FHIR Data

```bash
# Validate FHIR JSON file
php bin/console fhir:validate patient.json
```

### FHIRPath Commands

```bash
# Evaluate FHIRPath expression
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Evaluate with different output formats
php bin/console fhir:path:evaluate "name.given" patient.json --format=json --pretty
php bin/console fhir:path:evaluate "name.given" patient.json --format=count

# Show cache statistics
php bin/console fhir:path:evaluate "name" patient.json -v

# Validate FHIRPath expression syntax
php bin/console fhir:path:validate "name.where(use = 'official').given.first()"
```

## Usage Examples

### API Endpoint for FHIR Resources

```php
<?php

namespace App\Controller\Api;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/fhir')]
class FHIRApiController extends AbstractController
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    #[Route('/patient', methods: ['POST'])]
    public function createPatient(Request $request): JsonResponse
    {
        try {
            $patient = $this->serializer->deserialize(
                $request->getContent(),
                FHIRPatient::class,
                'json'
            );

            $errors = $this->serializer->validate($patient);
            if (!empty($errors)) {
                return new JsonResponse(['errors' => $errors], 400);
            }

            $json = $this->serializer->serialize($patient, 'json');
            return new JsonResponse(json_decode($json), 201);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
```

## Testing

### Unit Testing with Bundle Services

```php
<?php

namespace App\Tests\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FHIRServiceTest extends KernelTestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = static::getContainer()->get('fhir.serialization_service');
    }

    public function testPatientSerialization(): void
    {
        $patient = new FHIRPatient();
        $patient->setId('test-123');
        
        $json = $this->serializer->serialize($patient, 'json');
        $deserialized = $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        
        self::assertEquals('test-123', $deserialized->getId());
    }
}
```

## Requirements

- **PHP**: 8.2 or higher
- **Symfony**: 6.4 or 7.4
- **Dependencies**:
  - `ardenexal/fhir-code-generation`: ^1.0
  - `ardenexal/fhir-serialization`: ^1.0

## Documentation

For detailed documentation, see:

- **Component Guide**: `/docs/component-guides/fhir-bundle.md`
- **Architecture Overview**: `/docs/architecture.md`
- **Migration Guide**: `/docs/migration-guide.md`

## License

This bundle is released under the MIT License. See the LICENSE file for details.