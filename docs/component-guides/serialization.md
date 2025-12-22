# Serialization Component Guide

## Overview

The Serialization component provides comprehensive FHIR JSON serialization and deserialization capabilities. It can be used standalone or integrated with Symfony applications through the FHIRBundle.

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-serialization
```

### With FHIRBundle

The Serialization component is automatically included when you install the FHIRBundle:

```bash
composer require ardenexal/fhir-bundle
```

## Basic Usage

### Standalone Usage

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

// Create serialization service
$serializer = new FHIRSerializationService();

// Serialize FHIR resource to JSON
$patient = new FHIRPatient();
$patient->setId('example-123');
$json = $serializer->serialize($patient, 'json');

// Deserialize JSON to FHIR resource
$deserializedPatient = $serializer->deserialize($json, FHIRPatient::class, 'json');
```

### With Symfony DI

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

    public function patientToJson(FHIRPatient $patient): string
    {
        return $this->serializer->serialize($patient, 'json');
    }
}
```

## Core Components

### FHIRSerializationService

The main service for FHIR serialization operations.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;

$service = new FHIRSerializationService();

// Basic serialization
$json = $service->serialize($fhirResource, 'json');

// Serialization with context
$context = new FHIRSerializationContext();
$context->setStrictValidation(true);
$context->setPrettyPrint(true);

$json = $service->serialize($fhirResource, 'json', $context);

// Deserialization
$resource = $service->deserialize($json, FHIRPatient::class, 'json');

// Validation
$errors = $service->validate($fhirResource);
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "Validation error: {$error}\n";
    }
}
```

### Normalizers

The component includes specialized normalizers for different FHIR types:

#### FHIRResourceNormalizer

Handles FHIR resources (Patient, Observation, etc.):

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;

$normalizer = new FHIRResourceNormalizer();

// Check if can normalize
if ($normalizer->supportsNormalization($patient)) {
    $data = $normalizer->normalize($patient);
}

// Check if can denormalize
if ($normalizer->supportsDenormalization($data, FHIRPatient::class)) {
    $patient = $normalizer->denormalize($data, FHIRPatient::class);
}
```

#### FHIRComplexTypeNormalizer

Handles complex FHIR types (HumanName, Address, etc.):

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRComplexTypeNormalizer;

$normalizer = new FHIRComplexTypeNormalizer();

$humanName = new FHIRHumanName();
$humanName->setFamily('Doe');
$humanName->setGiven(['John']);

$data = $normalizer->normalize($humanName);
// Result: ['family' => 'Doe', 'given' => ['John']]

$denormalized = $normalizer->denormalize($data, FHIRHumanName::class);
```

#### FHIRPrimitiveTypeNormalizer

Handles FHIR primitive types (string, boolean, decimal, etc.):

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRPrimitiveTypeNormalizer;

$normalizer = new FHIRPrimitiveTypeNormalizer();

$fhirString = new FHIRString('Hello World');
$data = $normalizer->normalize($fhirString);
// Result: 'Hello World'

$denormalized = $normalizer->denormalize('Hello World', FHIRString::class);
```

### Validation

#### FHIRValidator

Validates FHIR resources against business rules:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;

$validator = new FHIRValidator();

$patient = new FHIRPatient();
// ... set patient properties

$violations = $validator->validate($patient);

foreach ($violations as $violation) {
    echo "Property: {$violation->getPropertyPath()}\n";
    echo "Message: {$violation->getMessage()}\n";
    echo "Invalid value: {$violation->getInvalidValue()}\n";
}
```

#### FHIRSchemaValidator

Validates FHIR resources against JSON schema:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRSchemaValidator;

$validator = new FHIRSchemaValidator();

$json = '{"resourceType": "Patient", "id": "example"}';
$isValid = $validator->validateJson($json, 'Patient');

if (!$isValid) {
    $errors = $validator->getErrors();
    foreach ($errors as $error) {
        echo "Schema error: {$error}\n";
    }
}
```

### Type Resolution

#### FHIRTypeResolver

Resolves FHIR types for serialization:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;

$resolver = new FHIRTypeResolver();

// Resolve resource type from JSON
$resourceType = $resolver->resolveResourceType($json);
echo "Resource type: {$resourceType}\n"; // e.g., "Patient"

// Get class name for resource type
$className = $resolver->getClassForResourceType('Patient');
echo "Class: {$className}\n"; // e.g., "FHIRPatient"

// Check if type is supported
if ($resolver->supportsType('Observation')) {
    echo "Observation is supported\n";
}
```

## Advanced Usage

### Custom Serialization Context

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;

$context = new FHIRSerializationContext();

// Validation settings
$context->setStrictValidation(true);
$context->setValidateSchema(true);
$context->setValidateBusinessRules(true);

// Output formatting
$context->setPrettyPrint(true);
$context->setIndentation('  '); // 2 spaces
$context->setEscapeUnicode(false);

// Performance settings
$context = new FHIRSerializationContext(
    skipNonEssentialValidation: true,
    omitNullValues: true,
    omitEmptyArrays: true
);

// Debug settings
$context->setDebugMode(true);
$context->setIncludeDebugInfo(true);

// Use context in serialization
$json = $serializer->serialize($patient, 'json', $context);
```

### Batch Operations

```php
<?php

class FHIRBatchProcessor
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    public function processBatch(array $jsonResources): array
    {
        $results = [];
        
        foreach ($jsonResources as $json) {
            try {
                // Determine resource type
                $data = json_decode($json, true);
                $resourceType = $data['resourceType'] ?? null;
                
                if (!$resourceType) {
                    throw new \InvalidArgumentException('Missing resourceType');
                }
                
                // Get appropriate class
                $className = "FHIR{$resourceType}";
                
                // Deserialize
                $resource = $this->serializer->deserialize($json, $className, 'json');
                
                // Validate
                $errors = $this->serializer->validate($resource);
                
                $results[] = [
                    'resource' => $resource,
                    'valid' => empty($errors),
                    'errors' => $errors
                ];
                
            } catch (\Exception $e) {
                $results[] = [
                    'resource' => null,
                    'valid' => false,
                    'errors' => [$e->getMessage()]
                ];
            }
        }
        
        return $results;
    }
}
```

### Custom Normalizers

Create custom normalizers for specific needs:

```php
<?php

namespace App\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CustomPatientNormalizer implements FHIRNormalizerInterface, NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        if (!$object instanceof FHIRPatient) {
            throw new \InvalidArgumentException('Expected FHIRPatient');
        }

        $data = [
            'resourceType' => 'Patient',
            'id' => $object->getId(),
            'name' => $this->normalizeName($object->getName()),
            // Add custom normalization logic
            'customField' => $this->getCustomField($object),
        ];

        return array_filter($data); // Remove null values
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): FHIRPatient
    {
        $patient = new FHIRPatient();
        
        if (isset($data['id'])) {
            $patient->setId($data['id']);
        }
        
        if (isset($data['name'])) {
            $patient->setName($this->denormalizeName($data['name']));
        }
        
        // Handle custom field
        if (isset($data['customField'])) {
            $this->setCustomField($patient, $data['customField']);
        }

        return $patient;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof FHIRPatient;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === FHIRPatient::class;
    }

    private function normalizeName(?FHIRHumanName $name): ?array
    {
        if (!$name) {
            return null;
        }

        return [
            'family' => $name->getFamily(),
            'given' => $name->getGiven(),
            'use' => $name->getUse(),
        ];
    }

    private function denormalizeName(array $data): FHIRHumanName
    {
        $name = new FHIRHumanName();
        
        if (isset($data['family'])) {
            $name->setFamily($data['family']);
        }
        
        if (isset($data['given'])) {
            $name->setGiven($data['given']);
        }
        
        if (isset($data['use'])) {
            $name->setUse($data['use']);
        }

        return $name;
    }
}
```

### Performance Optimization

#### Metadata Caching

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataCache;

// Configure caching
$cache = new FHIRMetadataCache();
$cache->setCacheDirectory('/path/to/cache');
$cache->setTtl(3600); // 1 hour

$serializer = new FHIRSerializationService();
$serializer->setMetadataCache($cache);

// Metadata will be cached automatically
$json = $serializer->serialize($patient, 'json');
```

#### Performance Optimization

```php
<?php

// Use performance-optimized context
$context = new FHIRSerializationContext(
    skipNonEssentialValidation: true,
    omitNullValues: true,
    omitEmptyArrays: true
);

// Only perform essential operations
$serializer->serialize($patient, 'json', $context->toSymfonyContext());
```

#### Memory Management

```php
<?php

class LargeFHIRProcessor
{
    private FHIRSerializationService $serializer;
    private int $batchSize = 100;

    public function processLargeDataset(array $resources): void
    {
        $batches = array_chunk($resources, $this->batchSize);
        
        foreach ($batches as $batch) {
            $this->processBatch($batch);
            
            // Force garbage collection
            gc_collect_cycles();
            
            // Monitor memory usage
            $memory = memory_get_usage(true);
            if ($memory > 500 * 1024 * 1024) { // 500MB
                throw new \RuntimeException('Memory limit exceeded');
            }
        }
    }

    private function processBatch(array $batch): void
    {
        foreach ($batch as $resource) {
            $json = $this->serializer->serialize($resource, 'json');
            // Process JSON...
            
            // Clear reference to help GC
            unset($json);
        }
    }
}
```

## Symfony Integration

### Service Configuration

```yaml
# config/services.yaml
services:
    # Custom normalizer
    App\Serialization\Normalizer\CustomPatientNormalizer:
        tags:
            - { name: fhir.normalizer, priority: 100 }

    # Custom validator
    App\Serialization\Validator\CustomFHIRValidator:
        tags:
            - { name: fhir.validator }
```

### Event Listeners

```php
<?php

namespace App\EventListener;

use Ardenexal\FHIRTools\Component\Serialization\Event\FHIRSerializationEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class FHIRSerializationListener
{
    #[AsEventListener(event: 'fhir.serialization.before')]
    public function onBeforeSerialization(FHIRSerializationEvent $event): void
    {
        $resource = $event->getResource();
        
        // Add custom logic before serialization
        if ($resource instanceof FHIRPatient) {
            // Log patient serialization
            // $this->logger->info('Serializing patient', ['id' => $resource->getId()]);
        }
    }

    #[AsEventListener(event: 'fhir.serialization.after')]
    public function onAfterSerialization(FHIRSerializationEvent $event): void
    {
        $json = $event->getResult();
        
        // Validate serialized JSON
        if (!json_decode($json)) {
            throw new \RuntimeException('Invalid JSON produced');
        }
    }
}
```

### Controller Integration

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
            // Deserialize request body
            $patient = $this->serializer->deserialize(
                $request->getContent(),
                FHIRPatient::class,
                'json'
            );

            // Validate
            $errors = $this->serializer->validate($patient);
            if (!empty($errors)) {
                return new JsonResponse([
                    'error' => 'Validation failed',
                    'details' => $errors
                ], 400);
            }

            // Save patient (implement your logic)
            // $this->patientRepository->save($patient);

            // Return serialized patient
            $json = $this->serializer->serialize($patient, 'json');
            
            return new JsonResponse(
                json_decode($json),
                201,
                ['Content-Type' => 'application/fhir+json']
            );

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Serialization error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/patient/{id}', methods: ['GET'])]
    public function getPatient(string $id): JsonResponse
    {
        // Load patient from database
        // $patient = $this->patientRepository->find($id);
        
        // For demo, create sample patient
        $patient = new FHIRPatient();
        $patient->setId($id);
        
        $name = new FHIRHumanName();
        $name->setFamily('Doe');
        $name->setGiven(['John']);
        $patient->setName([$name]);

        try {
            $json = $this->serializer->serialize($patient, 'json');
            
            return new JsonResponse(
                json_decode($json),
                200,
                ['Content-Type' => 'application/fhir+json']
            );

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Serialization error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
```

## Testing

### Unit Testing

```php
<?php

namespace Tests\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

class FHIRSerializationServiceTest extends TestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = new FHIRSerializationService();
    }

    public function testPatientSerialization(): void
    {
        $patient = new FHIRPatient();
        $patient->setId('test-123');
        
        $name = new FHIRHumanName();
        $name->setFamily('Doe');
        $name->setGiven(['John']);
        $patient->setName([$name]);

        $json = $this->serializer->serialize($patient, 'json');
        
        self::assertJson($json);
        
        $data = json_decode($json, true);
        self::assertEquals('Patient', $data['resourceType']);
        self::assertEquals('test-123', $data['id']);
        self::assertEquals('Doe', $data['name'][0]['family']);
        self::assertEquals(['John'], $data['name'][0]['given']);
    }

    public function testPatientDeserialization(): void
    {
        $json = json_encode([
            'resourceType' => 'Patient',
            'id' => 'test-456',
            'name' => [
                [
                    'family' => 'Smith',
                    'given' => ['Jane']
                ]
            ]
        ]);

        $patient = $this->serializer->deserialize($json, FHIRPatient::class, 'json');

        self::assertInstanceOf(FHIRPatient::class, $patient);
        self::assertEquals('test-456', $patient->getId());
        self::assertEquals('Smith', $patient->getName()[0]->getFamily());
        self::assertEquals(['Jane'], $patient->getName()[0]->getGiven());
    }

    public function testRoundTripSerialization(): void
    {
        $originalPatient = new FHIRPatient();
        $originalPatient->setId('round-trip-test');
        
        // Serialize
        $json = $this->serializer->serialize($originalPatient, 'json');
        
        // Deserialize
        $deserializedPatient = $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        
        // Verify round trip
        self::assertEquals($originalPatient->getId(), $deserializedPatient->getId());
    }

    public function testValidation(): void
    {
        $patient = new FHIRPatient();
        // Don't set required fields to trigger validation errors
        
        $errors = $this->serializer->validate($patient);
        
        self::assertNotEmpty($errors);
        // Add specific validation assertions based on your FHIR model requirements
    }
}
```

### Property-Based Testing

```php
<?php

namespace Tests\Property;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

class SerializationPropertyTest extends TestCase
{
    use TestTrait;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = new FHIRSerializationService();
    }

    /**
     * **Feature: repository-reorganization, Property 21: Serialization round trip**
     * **Validates: Requirements 8.2**
     */
    public function testSerializationRoundTrip(): void
    {
        $this->forAll(
            Generator\string(),
            Generator\seq(Generator\string())
        )->then(function (string $id, array $givenNames) {
            // Create patient with random data
            $patient = new FHIRPatient();
            $patient->setId($id);
            
            if (!empty($givenNames)) {
                $name = new FHIRHumanName();
                $name->setGiven($givenNames);
                $patient->setName([$name]);
            }

            // Serialize and deserialize
            $json = $this->serializer->serialize($patient, 'json');
            $deserialized = $this->serializer->deserialize($json, FHIRPatient::class, 'json');

            // Verify round trip preserves data
            self::assertEquals($patient->getId(), $deserialized->getId());
            
            if ($patient->getName()) {
                self::assertEquals(
                    $patient->getName()[0]->getGiven(),
                    $deserialized->getName()[0]->getGiven()
                );
            }
        });
    }
}
```

### Integration Testing

```php
<?php

namespace Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SerializationIntegrationTest extends KernelTestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = static::getContainer()->get('fhir.serialization_service');
    }

    public function testSymfonyIntegration(): void
    {
        // Test that the service is properly configured in Symfony
        self::assertInstanceOf(FHIRSerializationService::class, $this->serializer);
        
        // Test basic functionality
        $patient = new FHIRPatient();
        $patient->setId('integration-test');
        
        $json = $this->serializer->serialize($patient, 'json');
        self::assertJson($json);
        
        $deserialized = $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        self::assertEquals('integration-test', $deserialized->getId());
    }

    public function testRealWorldFHIRData(): void
    {
        // Load real FHIR example
        $exampleJson = file_get_contents(__DIR__ . '/../../Fixtures/OfficialFHIR/patient-example.json');
        
        // Test deserialization
        $patient = $this->serializer->deserialize($exampleJson, FHIRPatient::class, 'json');
        self::assertInstanceOf(FHIRPatient::class, $patient);
        
        // Test re-serialization
        $reserializedJson = $this->serializer->serialize($patient, 'json');
        self::assertJson($reserializedJson);
        
        // Verify data integrity
        $originalData = json_decode($exampleJson, true);
        $reserializedData = json_decode($reserializedJson, true);
        
        self::assertEquals($originalData['resourceType'], $reserializedData['resourceType']);
        self::assertEquals($originalData['id'], $reserializedData['id']);
    }
}
```

## Error Handling

### Exception Types

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Exception\SerializationException;
use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;

try {
    $patient = $serializer->deserialize($invalidJson, FHIRPatient::class, 'json');
} catch (SerializationException $e) {
    echo "Serialization error: " . $e->getMessage();
} catch (ValidationException $e) {
    echo "Validation error: " . $e->getMessage();
    
    // Get detailed validation errors
    $violations = $e->getViolations();
    foreach ($violations as $violation) {
        echo "Field: {$violation->getPropertyPath()}, Error: {$violation->getMessage()}\n";
    }
}
```

### Error Recovery

```php
<?php

class RobustFHIRProcessor
{
    public function __construct(
        private readonly FHIRSerializationService $serializer
    ) {}

    public function processWithErrorRecovery(string $json): ?FHIRPatient
    {
        try {
            return $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        } catch (SerializationException $e) {
            // Try to recover by fixing common issues
            $fixedJson = $this->attemptJsonFix($json);
            
            if ($fixedJson !== $json) {
                try {
                    return $this->serializer->deserialize($fixedJson, FHIRPatient::class, 'json');
                } catch (SerializationException $e2) {
                    // Log both errors
                    error_log("Original error: " . $e->getMessage());
                    error_log("Recovery error: " . $e2->getMessage());
                }
            }
            
            return null;
        }
    }

    private function attemptJsonFix(string $json): string
    {
        // Fix common JSON issues
        $data = json_decode($json, true);
        
        if (!$data) {
            return $json; // Can't fix invalid JSON
        }
        
        // Ensure resourceType is present
        if (!isset($data['resourceType'])) {
            $data['resourceType'] = 'Patient';
        }
        
        // Fix common field issues
        if (isset($data['name']) && !is_array($data['name'])) {
            $data['name'] = [$data['name']];
        }
        
        return json_encode($data);
    }
}
```

## Best Practices

### Performance

1. **Enable Caching**: Use metadata caching for better performance
2. **Batch Processing**: Process multiple resources efficiently
3. **Memory Management**: Monitor memory usage with large datasets
4. **Lazy Loading**: Use lazy loading for better resource utilization

### Security

1. **Input Validation**: Always validate FHIR data before processing
2. **Schema Validation**: Use schema validation for additional security
3. **Error Handling**: Don't expose sensitive information in error messages
4. **Resource Limits**: Set appropriate limits for resource processing

### Maintainability

1. **Custom Normalizers**: Create custom normalizers for specific needs
2. **Event Listeners**: Use events for cross-cutting concerns
3. **Testing**: Implement comprehensive testing including property-based tests
4. **Documentation**: Document custom serialization logic

### Integration

1. **Symfony Services**: Use dependency injection for better testability
2. **Configuration**: Make serialization behavior configurable
3. **Monitoring**: Monitor serialization performance and errors
4. **Logging**: Add appropriate logging for debugging and auditing