# FHIR Serialization Component

Standalone PHP library for FHIR JSON serialization and deserialization. This component provides comprehensive FHIR data handling capabilities and can be used independently or as part of the FHIRBundle in Symfony applications.

## Features

- **Standalone Library**: Use independently without Symfony
- **FHIR JSON Serialization**: Complete FHIR JSON serialization and deserialization
- **Validation**: Built-in FHIR validation capabilities
- **Symfony Integration**: Seamless integration with Symfony Serializer
- **Performance Optimized**: Metadata caching and lazy loading
- **Type Resolution**: Automatic FHIR type detection and resolution
- **Error Handling**: Comprehensive error reporting and validation
- **Extensible**: Custom normalizers and validators

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-serialization
```

### With FHIRBundle

```bash
composer require ardenexal/fhir-bundle
```

## Quick Start

### Basic Usage

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

### With Validation

```php
<?php

// Deserialize with validation
$patient = $serializer->deserialize($json, FHIRPatient::class, 'json');

// Validate FHIR resource
$errors = $serializer->validate($patient);
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "Validation error: {$error}\n";
    }
}
```

## Core Components

### FHIRSerializationService

Main service for FHIR serialization operations:

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
```

### Normalizers

Specialized normalizers for different FHIR types:

#### FHIRResourceNormalizer

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;

$normalizer = new FHIRResourceNormalizer();

if ($normalizer->supportsNormalization($patient)) {
    $data = $normalizer->normalize($patient);
}

if ($normalizer->supportsDenormalization($data, FHIRPatient::class)) {
    $patient = $normalizer->denormalize($data, FHIRPatient::class);
}
```

#### FHIRComplexTypeNormalizer

```php
<?php

$normalizer = new FHIRComplexTypeNormalizer();

$humanName = new FHIRHumanName();
$humanName->setFamily('Doe');
$humanName->setGiven(['John']);

$data = $normalizer->normalize($humanName);
// Result: ['family' => 'Doe', 'given' => ['John']]
```

#### FHIRPrimitiveTypeNormalizer

```php
<?php

$normalizer = new FHIRPrimitiveTypeNormalizer();

$fhirString = new FHIRString('Hello World');
$data = $normalizer->normalize($fhirString);
// Result: 'Hello World'
```

### Validation

#### FHIRValidator

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;

$validator = new FHIRValidator();

$violations = $validator->validate($patient);
foreach ($violations as $violation) {
    echo "Property: {$violation->getPropertyPath()}\n";
    echo "Message: {$violation->getMessage()}\n";
}
```

#### FHIRSchemaValidator

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

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;

$resolver = new FHIRTypeResolver();

// Resolve resource type from JSON
$resourceType = $resolver->resolveResourceType($json);
echo "Resource type: {$resourceType}\n";

// Get class name for resource type
$className = $resolver->getClassForResourceType('Patient');
echo "Class: {$className}\n";

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

// Create context with specific settings
$context = FHIRSerializationContext::withStrictValidation();

// Or create with custom configuration
$context = new FHIRSerializationContext(
    format: FHIRSerializationContext::FORMAT_JSON,
    validationMode: FHIRSerializationContext::VALIDATION_STRICT,
    includeExtensions: true,
    includeMetadata: true,
    enableDebugInfo: false,
    omitNullValues: true
);

// Use context in serialization
$json = $serializer->serialize($patient, 'json', $context->toSymfonyContext());
```

### Batch Processing

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
                $data = json_decode($json, true);
                $resourceType = $data['resourceType'] ?? null;
                
                if (!$resourceType) {
                    throw new \InvalidArgumentException('Missing resourceType');
                }
                
                $className = "FHIR{$resourceType}";
                $resource = $this->serializer->deserialize($json, $className, 'json');
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

        return [
            'resourceType' => 'Patient',
            'id' => $object->getId(),
            'name' => $this->normalizeName($object->getName()),
            'customField' => $this->getCustomField($object),
        ];
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
}
```

## Performance Optimization

### Metadata Caching

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataCache;

$cache = new FHIRMetadataCache();
$cache->setCacheDirectory('/path/to/cache');
$cache->setTtl(3600); // 1 hour

$serializer = new FHIRSerializationService();
$serializer->setMetadataCache($cache);
```

### Performance Optimization

```php
<?php

// Use performance-optimized context
$context = new FHIRSerializationContext(
    skipNonEssentialValidation: true,
    omitNullValues: true,
    omitEmptyArrays: true
);

$serializer->serialize($patient, 'json', $context->toSymfonyContext());
```

### Memory Management

```php
<?php

class LargeFHIRProcessor
{
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

### Controller Integration

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
        
        $json = $this->serializer->serialize($patient, 'json');
        
        self::assertJson($json);
        
        $data = json_decode($json, true);
        self::assertEquals('Patient', $data['resourceType']);
        self::assertEquals('test-123', $data['id']);
    }

    public function testRoundTripSerialization(): void
    {
        $originalPatient = new FHIRPatient();
        $originalPatient->setId('round-trip-test');
        
        $json = $this->serializer->serialize($originalPatient, 'json');
        $deserializedPatient = $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        
        self::assertEquals($originalPatient->getId(), $deserializedPatient->getId());
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

    public function testSerializationRoundTrip(): void
    {
        $this->forAll(
            Generator\string(),
            Generator\seq(Generator\string())
        )->then(function (string $id, array $givenNames) {
            $patient = new FHIRPatient();
            $patient->setId($id);
            
            if (!empty($givenNames)) {
                $name = new FHIRHumanName();
                $name->setGiven($givenNames);
                $patient->setName([$name]);
            }

            $serializer = new FHIRSerializationService();
            $json = $serializer->serialize($patient, 'json');
            $deserialized = $serializer->deserialize($json, FHIRPatient::class, 'json');

            self::assertEquals($patient->getId(), $deserialized->getId());
        });
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
    public function processWithErrorRecovery(string $json): ?FHIRPatient
    {
        try {
            return $this->serializer->deserialize($json, FHIRPatient::class, 'json');
        } catch (SerializationException $e) {
            $fixedJson = $this->attemptJsonFix($json);
            
            if ($fixedJson !== $json) {
                try {
                    return $this->serializer->deserialize($fixedJson, FHIRPatient::class, 'json');
                } catch (SerializationException $e2) {
                    error_log("Recovery failed: " . $e2->getMessage());
                }
            }
            
            return null;
        }
    }

    private function attemptJsonFix(string $json): string
    {
        $data = json_decode($json, true);
        
        if (!$data) {
            return $json;
        }
        
        // Fix common issues
        if (!isset($data['resourceType'])) {
            $data['resourceType'] = 'Patient';
        }
        
        return json_encode($data);
    }
}
```

## Requirements

- **PHP**: 8.2 or higher
- **Dependencies**:
  - `symfony/serializer`: ^6.4|^7.4
  - `symfony/validator`: ^6.4|^7.4

## Documentation

For detailed documentation, see:

- **Component Guide**: `/docs/component-guides/serialization.md`
- **Architecture Overview**: `/docs/architecture.md`
- **Migration Guide**: `/docs/migration-guide.md`

## License

This component is released under the MIT License. See the LICENSE file for details.