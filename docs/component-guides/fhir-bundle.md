# FHIRBundle Component Guide

## Overview

The FHIRBundle is a Symfony Bundle that provides seamless integration of FHIR Tools components into Symfony applications. It automatically configures services, provides semantic configuration, and integrates with Symfony's dependency injection container.

## Installation

### Via Composer (Recommended)

```bash
composer require ardenexal/fhir-bundle
```

If you have Symfony Flex installed, the bundle will be automatically configured. Otherwise, follow the manual installation steps below.

### Manual Installation

1. Register the bundle in `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

2. Create the configuration file `config/packages/fhir.yaml`:

```yaml
fhir:
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'
        base_namespace: 'App\\FHIR'
        generate_tests: false
    serialization:
        strict_validation: true
        cache_metadata: true
```

## Configuration

### Full Configuration Reference

```yaml
fhir:
    # Code Generation Configuration
    generation:
        output_directory: '%kernel.project_dir%/src/FHIR'  # Where to generate FHIR classes
        base_namespace: 'App\\FHIR'                        # Base namespace for generated classes
        generate_tests: false                              # Whether to generate test files
        
    # Serialization Configuration  
    serialization:
        strict_validation: true                            # Enable strict FHIR validation
        cache_metadata: true                               # Cache metadata for performance
        debug: false                                       # Enable debug mode
```

### Environment-Specific Configuration

```yaml
# config/packages/dev/fhir.yaml
fhir:
    serialization:
        debug: true
        
# config/packages/prod/fhir.yaml
fhir:
    serialization:
        cache_metadata: true
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

### Available Services

The bundle registers the following services:

| Service ID | Class | Description |
|------------|-------|-------------|
| `fhir.model_generator` | `FHIRModelGenerator` | Generates FHIR model classes |
| `fhir.valueset_generator` | `FHIRValueSetGenerator` | Generates FHIR value set enums |
| `fhir.serialization_service` | `FHIRSerializationService` | FHIR serialization and deserialization |
| `fhir.package_loader` | `PackageLoader` | Loads FHIR packages |
| `fhir.builder_context` | `BuilderContext` | Code generation context |

### Service Injection

#### In Controllers

```php
<?php

namespace App\Controller;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FHIRController extends AbstractController
{
    public function __construct(
        private readonly FHIRModelGenerator $generator,
        private readonly FHIRSerializationService $serializer
    ) {}

    public function generateModels(): Response
    {
        try {
            $this->generator->generate('R4B');
            return new Response('FHIR models generated successfully');
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function serializePatient(): Response
    {
        // Example FHIR Patient serialization
        $patient = new \App\FHIR\FHIRPatient();
        $patient->setId('example-123');
        
        $json = $this->serializer->serialize($patient, 'json');
        
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}
```

#### In Commands

```php
<?php

namespace App\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-fhir',
    description: 'Generate FHIR model classes'
)]
class GenerateFHIRCommand extends Command
{
    public function __construct(
        private readonly FHIRModelGenerator $generator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('version', InputArgument::REQUIRED, 'FHIR version (e.g., R4B)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $version = $input->getArgument('version');
        
        $output->writeln("Generating FHIR models for version: {$version}");
        
        try {
            $this->generator->generate($version);
            $output->writeln('<info>FHIR models generated successfully!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
```

#### In Services

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

class FHIRProcessingService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    public function processPatientData(string $jsonData): array
    {
        // Deserialize JSON to FHIR Patient
        $patient = $this->serializer->deserialize(
            $jsonData, 
            \App\FHIR\FHIRPatient::class, 
            'json'
        );

        // Process the patient data
        $processedData = [
            'id' => $patient->getId(),
            'name' => $patient->getName()?->getFamily(),
            'birthDate' => $patient->getBirthDate(),
        ];

        return $processedData;
    }
}
```

### Service Configuration

#### Custom Service Configuration

```yaml
# config/services.yaml
services:
    # Custom FHIR service
    App\Service\CustomFHIRService:
        arguments:
            $generator: '@fhir.model_generator'
            $serializer: '@fhir.serialization_service'
            
    # Override default configuration
    fhir.model_generator:
        class: App\Service\CustomFHIRModelGenerator
        decorates: fhir.model_generator
```

## Commands

### Built-in Commands

The bundle provides several console commands:

#### Generate FHIR Models

```bash
# Generate models for FHIR R4B
php bin/console fhir:generate R4B

# Generate models with custom output directory
php bin/console fhir:generate R4B --output-dir=/custom/path

# Generate models with verbose output
php bin/console fhir:generate R4B -v
```

#### Validate FHIR Data

```bash
# Validate FHIR JSON file
php bin/console fhir:validate patient.json

# Validate with specific FHIR version
php bin/console fhir:validate patient.json --version=R4B
```

### Creating Custom Commands

```php
<?php

namespace App\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fhir-status',
    description: 'Show FHIR Tools status'
)]
class FHIRStatusCommand extends Command
{
    public function __construct(
        private readonly FHIRModelGenerator $generator,
        private readonly FHIRSerializationService $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('FHIR Tools Status:');
        $output->writeln('- Generator: Available');
        $output->writeln('- Serializer: Available');
        
        return Command::SUCCESS;
    }
}
```

## Integration Examples

### API Endpoints

#### REST API for FHIR Resources

```php
<?php

namespace App\Controller\Api;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            // Deserialize incoming JSON to FHIR Patient
            $patient = $this->serializer->deserialize(
                $request->getContent(),
                \App\FHIR\FHIRPatient::class,
                'json'
            );

            // Validate the patient data
            $errors = $this->serializer->validate($patient);
            if (!empty($errors)) {
                return new JsonResponse(['errors' => $errors], 400);
            }

            // Save patient (implement your persistence logic)
            // $this->patientRepository->save($patient);

            // Return serialized patient
            $json = $this->serializer->serialize($patient, 'json');
            
            return new JsonResponse(json_decode($json), 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/patient/{id}', methods: ['GET'])]
    public function getPatient(string $id): JsonResponse
    {
        // Load patient from database (implement your logic)
        // $patient = $this->patientRepository->find($id);
        
        // For demo, create a sample patient
        $patient = new \App\FHIR\FHIRPatient();
        $patient->setId($id);
        
        $json = $this->serializer->serialize($patient, 'json');
        
        return new JsonResponse(json_decode($json));
    }
}
```

### Event Listeners

#### FHIR Data Processing Events

```php
<?php

namespace App\EventListener;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class FHIRDataListener
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    #[AsEventListener(event: 'fhir.patient.created')]
    public function onPatientCreated(PatientCreatedEvent $event): void
    {
        $patient = $event->getPatient();
        
        // Serialize for logging
        $json = $this->serializer->serialize($patient, 'json');
        
        // Log the patient creation
        // $this->logger->info('Patient created', ['patient' => $json]);
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

class FHIRProcessingServiceTest extends KernelTestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        
        $this->serializer = static::getContainer()->get('fhir.serialization_service');
    }

    public function testPatientSerialization(): void
    {
        $patient = new \App\FHIR\FHIRPatient();
        $patient->setId('test-123');
        
        $json = $this->serializer->serialize($patient, 'json');
        $deserialized = $this->serializer->deserialize($json, \App\FHIR\FHIRPatient::class, 'json');
        
        self::assertEquals('test-123', $deserialized->getId());
    }
}
```

### Integration Testing

```php
<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FHIRControllerTest extends WebTestCase
{
    public function testGenerateModels(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/fhir/generate');
        
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('generated successfully', $client->getResponse()->getContent());
    }

    public function testSerializePatient(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/fhir/serialize-patient');
        
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());
    }
}
```

## Performance Optimization

### Service Caching

```yaml
# config/packages/prod/fhir.yaml
fhir:
    serialization:
        cache_metadata: true
        
# Enable Symfony cache
framework:
    cache:
        app: cache.adapter.filesystem
```

### Lazy Loading

```yaml
# config/services.yaml
services:
    fhir.model_generator:
        lazy: true
        
    fhir.serialization_service:
        lazy: true
```

### Memory Management

```php
<?php

// In your services, implement proper cleanup
class FHIRProcessingService
{
    public function processLargeDataset(array $data): void
    {
        foreach ($data as $item) {
            $this->processItem($item);
            
            // Force garbage collection for large datasets
            if (memory_get_usage() > 100 * 1024 * 1024) { // 100MB
                gc_collect_cycles();
            }
        }
    }
}
```

## Troubleshooting

### Common Issues

#### Bundle Not Registered

**Error**: `Bundle "FHIRBundle" not found`

**Solution**: Ensure the bundle is registered in `config/bundles.php`

#### Service Not Found

**Error**: `Service "fhir.model_generator" not found`

**Solution**: Clear cache and check bundle configuration:

```bash
php bin/console cache:clear
php bin/console debug:container fhir
```

#### Configuration Errors

**Error**: `Invalid configuration for path "fhir"`

**Solution**: Validate your configuration:

```bash
php bin/console config:dump fhir
php bin/console lint:yaml config/packages/fhir.yaml
```

### Debug Commands

```bash
# List all FHIR services
php bin/console debug:container fhir

# Show FHIR configuration
php bin/console debug:config fhir

# Check bundle status
php bin/console debug:bundle FHIRBundle
```

## Best Practices

### Configuration Management

1. **Environment Variables**: Use environment variables for environment-specific settings
2. **Validation**: Validate configuration in development
3. **Documentation**: Document custom configuration options

### Service Usage

1. **Dependency Injection**: Always use DI instead of service locator
2. **Interface Programming**: Program against interfaces when possible
3. **Error Handling**: Implement proper error handling for FHIR operations

### Performance

1. **Lazy Loading**: Use lazy services for expensive operations
2. **Caching**: Enable metadata caching in production
3. **Memory Management**: Monitor memory usage with large FHIR datasets

### Security

1. **Input Validation**: Always validate FHIR data inputs
2. **Error Messages**: Don't expose sensitive information in error messages
3. **Access Control**: Implement proper access control for FHIR endpoints