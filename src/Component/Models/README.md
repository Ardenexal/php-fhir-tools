# FHIR Models Component

Standalone PHP library containing pre-generated FHIR model classes for R4, R4B, and R5 versions. This component provides a centralized repository of FHIR classes that can be reused across other components (CodeGeneration, Serialization, and FHIRBundle) without requiring each component to generate its own models.

## Features

- **Pre-Generated Models**: Complete FHIR model classes for R4, R4B, and R5
- **Version Isolation**: Separate namespaces prevent conflicts between FHIR versions
- **Comprehensive Coverage**: All core resources, data types, primitives, and enums
- **Minimal Dependencies**: Only PHP 8.2+ required
- **Component Integration**: Seamless integration with other FHIR Tools components
- **Utility Classes**: Helper classes for model registry and version detection
- **Code Quality**: PSR-12 compliant with comprehensive PHPDoc

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-models
```

### With FHIRBundle

```bash
composer require ardenexal/fhir-bundle
```

## Quick Start

### Basic Usage

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

// Create a new patient
$patient = new FHIRPatient(
    id: new FHIRString('patient-123'),
    name: [
        new FHIRHumanName(
            family: new FHIRString('Doe'),
            given: [new FHIRString('John')]
        )
    ]
);

// Access patient properties
echo $patient->id->value; // 'patient-123'
echo $patient->name[0]->family->value; // 'Doe'
```

### Using ModelRegistry

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;

// Get class names for different FHIR types
$patientClass = ModelRegistry::getResourceClass('R4B', 'Patient');
$nameClass = ModelRegistry::getDataTypeClass('R4B', 'HumanName');
$stringClass = ModelRegistry::getPrimitiveClass('R4B', 'String');

// Create instances dynamically
$patient = new $patientClass();
$name = new $nameClass();
$id = new $stringClass('dynamic-id');
```

### Version Detection

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPatient;

$patient = new FHIRPatient();

// Detect FHIR version from instance
$version = VersionDetector::detectVersion($patient); // 'R5'

// Detect from class name
$version = VersionDetector::detectVersionFromClassName(FHIRPatient::class); // 'R5'

// Check if class belongs to Models component
$isModelsClass = VersionDetector::isModelsComponentClass(FHIRPatient::class); // true
```

## Architecture

### Namespace Organization

The component organizes FHIR models in version-specific namespaces:

```
Ardenexal\FHIRTools\Component\Models\
├── R4\
│   ├── Resource\          # FHIR Resources (Patient, Observation, etc.)
│   ├── DataType\          # Complex Data Types (HumanName, Address, etc.)
│   ├── Primitive\         # Primitive Types (FHIRString, FHIRInteger, etc.)
│   └── Enum\              # Value Set Enums (AdministrativeGender, etc.)
├── R4B\
│   ├── Resource\
│   ├── DataType\
│   ├── Primitive\
│   └── Enum\
├── R5\
│   ├── Resource\
│   ├── DataType\
│   ├── Primitive\
│   └── Enum\
└── Utility\               # Helper classes (ModelRegistry, VersionDetector)
```

### Backbone Elements

Backbone elements are organized within their parent resource directories:

```
R4B\Resource\
├── FHIRPatient.php
├── Patient\
│   ├── FHIRPatientContact.php
│   ├── FHIRPatientCommunication.php
│   └── FHIRPatientLink.php
├── FHIRObservation.php
└── Observation\
    ├── FHIRObservationReferenceRange.php
    └── FHIRObservationComponent.php
```

## Core Components

### ModelRegistry

Central registry for accessing FHIR models across versions:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;

// Resource classes
$patientClass = ModelRegistry::getResourceClass('R4B', 'Patient');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient'

// Backbone element classes
$contactClass = ModelRegistry::getBackboneElementClass('R4B', 'Patient', 'Contact');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Patient\FHIRPatientContact'

// Data type classes
$nameClass = ModelRegistry::getDataTypeClass('R4B', 'HumanName');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName'

// Primitive classes
$stringClass = ModelRegistry::getPrimitiveClass('R4B', 'String');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString'

// Enum classes
$genderEnum = ModelRegistry::getEnumClass('R4B', 'AdministrativeGender');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRAdministrativeGender'

// Code type classes (enum wrappers)
$genderType = ModelRegistry::getCodeTypeClass('R4B', 'AdministrativeGender');
// Returns: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdministrativeGenderType'

// Check supported versions
$versions = ModelRegistry::getSupportedVersions(); // ['R4', 'R4B', 'R5']
$isSupported = ModelRegistry::isSupportedVersion('R4B'); // true
```

### VersionDetector

Utility for detecting FHIR versions from model instances:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;

$patient = new \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient();

// Detect version from instance
$version = VersionDetector::detectVersion($patient); // 'R4B'

// Detect version from class name
$className = 'Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRObservation';
$version = VersionDetector::detectVersionFromClassName($className); // 'R5'

// Check if class belongs to Models component
$isModelsClass = VersionDetector::isModelsComponentClass($className); // true

// Non-Models component class
$otherClass = 'App\Entity\Patient';
$isModelsClass = VersionDetector::isModelsComponentClass($otherClass); // false
```

## FHIR Version Support

### R4 (FHIR 4.0.1)

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdministrativeGender;

$patient = new FHIRPatient();
$name = new FHIRHumanName();
$gender = FHIRAdministrativeGender::MALE;
```

### R4B (FHIR 4.3.0)

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRAdministrativeGender;

$patient = new FHIRPatient();
$name = new FHIRHumanName();
$gender = FHIRAdministrativeGender::FEMALE;
```

### R5 (FHIR 5.0.0)

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAdministrativeGender;

$patient = new FHIRPatient();
$name = new FHIRHumanName();
$gender = FHIRAdministrativeGender::OTHER;
```

## Model Types

### Resources

Core FHIR resources like Patient, Observation, Practitioner:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRObservation;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPractitioner;

$patient = new FHIRPatient();
$observation = new FHIRObservation();
$practitioner = new FHIRPractitioner();
```

### Data Types

Complex FHIR data types like HumanName, Address, ContactPoint:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;

$name = new FHIRHumanName();
$address = new FHIRAddress();
$contact = new FHIRContactPoint();
```

### Primitive Types

FHIR primitive types like string, integer, boolean:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;

$id = new FHIRString('patient-123');
$age = new FHIRInteger(35);
$active = new FHIRBoolean(true);
```

### Enums

FHIR value set enums:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRAdministrativeGender;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRObservationStatus;

$gender = FHIRAdministrativeGender::MALE;
$status = FHIRObservationStatus::FINAL;

// Get all enum values
$genders = FHIRAdministrativeGender::cases();
foreach ($genders as $gender) {
    echo $gender->value . "\n"; // 'male', 'female', 'other', 'unknown'
}
```

### Code Types

Wrapper classes for enums that extend FHIR primitive types:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRAdministrativeGender;

// Create from enum
$genderType = new FHIRAdministrativeGenderType(FHIRAdministrativeGender::MALE);

// Create from string
$genderType = new FHIRAdministrativeGenderType('female');

// Access the enum value
$enumValue = $genderType->value; // FHIRAdministrativeGender::FEMALE or 'female'
```

## Component Integration

### With Serialization Component

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

$patient = new FHIRPatient();
$serializer = new FHIRSerializationService();

// Serialize to JSON
$json = $serializer->serialize($patient, 'json');

// Deserialize from JSON
$deserializedPatient = $serializer->deserialize($json, FHIRPatient::class, 'json');
```

### With CodeGeneration Component

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;

// Extend existing models
class CustomPatient extends FHIRPatient
{
    public function getFullName(): string
    {
        $names = $this->name ?? [];
        if (empty($names)) {
            return '';
        }
        
        $name = $names[0];
        $family = $name->family?->value ?? '';
        $given = array_map(fn($g) => $g->value, $name->given ?? []);
        
        return trim(implode(' ', $given) . ' ' . $family);
    }
}
```

### With FHIRBundle

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;

class PatientService
{
    public function createPatient(array $data): FHIRPatient
    {
        // Models are automatically available through dependency injection
        $patientClass = ModelRegistry::getResourceClass('R4B', 'Patient');
        return new $patientClass();
    }
}
```

## Advanced Usage

### Dynamic Model Creation

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;

class DynamicFHIRFactory
{
    public function createResource(string $version, string $resourceType, array $data = []): object
    {
        $className = ModelRegistry::getResourceClass($version, $resourceType);
        
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Resource {$resourceType} not found for version {$version}");
        }
        
        $resource = new $className();
        
        // Populate with data
        foreach ($data as $property => $value) {
            if (property_exists($resource, $property)) {
                $resource->$property = $value;
            }
        }
        
        return $resource;
    }
    
    public function createDataType(string $version, string $dataType, array $data = []): object
    {
        $className = ModelRegistry::getDataTypeClass($version, $dataType);
        
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("DataType {$dataType} not found for version {$version}");
        }
        
        return new $className(...$data);
    }
}

// Usage
$factory = new DynamicFHIRFactory();
$patient = $factory->createResource('R4B', 'Patient', ['id' => 'test-123']);
$name = $factory->createDataType('R4B', 'HumanName', ['family' => 'Doe']);
```

### Cross-Version Compatibility

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;

class CrossVersionPatientProcessor
{
    public function processPatient(object $patient): array
    {
        $version = VersionDetector::detectVersion($patient);
        
        if (!$version) {
            throw new \InvalidArgumentException('Unable to detect FHIR version');
        }
        
        // Process based on version
        return match($version) {
            'R4' => $this->processR4Patient($patient),
            'R4B' => $this->processR4BPatient($patient),
            'R5' => $this->processR5Patient($patient),
            default => throw new \InvalidArgumentException("Unsupported version: {$version}")
        };
    }
    
    private function processR4Patient($patient): array
    {
        // R4-specific processing
        return ['version' => 'R4', 'id' => $patient->id?->value];
    }
    
    private function processR4BPatient($patient): array
    {
        // R4B-specific processing
        return ['version' => 'R4B', 'id' => $patient->id?->value];
    }
    
    private function processR5Patient($patient): array
    {
        // R5-specific processing
        return ['version' => 'R5', 'id' => $patient->id?->value];
    }
}
```

## Testing

### Unit Testing

```php
<?php

namespace Tests\Models;

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use PHPUnit\Framework\TestCase;

class FHIRModelsTest extends TestCase
{
    public function testPatientCreation(): void
    {
        $patient = new FHIRPatient(
            id: new FHIRString('test-123')
        );
        
        self::assertInstanceOf(FHIRPatient::class, $patient);
        self::assertEquals('test-123', $patient->id->value);
    }
    
    public function testModelRegistry(): void
    {
        $patientClass = ModelRegistry::getResourceClass('R4B', 'Patient');
        
        self::assertEquals(FHIRPatient::class, $patientClass);
        self::assertTrue(class_exists($patientClass));
    }
    
    public function testVersionIsolation(): void
    {
        $r4PatientClass = ModelRegistry::getResourceClass('R4', 'Patient');
        $r4bPatientClass = ModelRegistry::getResourceClass('R4B', 'Patient');
        $r5PatientClass = ModelRegistry::getResourceClass('R5', 'Patient');
        
        self::assertNotEquals($r4PatientClass, $r4bPatientClass);
        self::assertNotEquals($r4bPatientClass, $r5PatientClass);
        self::assertNotEquals($r4PatientClass, $r5PatientClass);
    }
}
```

### Property-Based Testing

```php
<?php

namespace Tests\Property;

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

class ModelsPropertyTest extends TestCase
{
    use TestTrait;
    
    /**
     * Feature: fhir-models-component, Property 1: Version-specific namespace isolation
     */
    public function testVersionSpecificNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Patient', 'Observation', 'Practitioner'])
        )->then(function (string $version, string $resourceName) {
            $className = ModelRegistry::getResourceClass($version, $resourceName);
            
            // Verify the class is in the correct version namespace
            self::assertStringContains("\\{$version}\\Resource\\", $className);
            
            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getResourceClass($otherVersion, $resourceName);
                self::assertNotEquals($className, $otherClassName);
            }
        });
    }
}
```

## Error Handling

### Exception Types

```php
<?php

use Ardenexal\FHIRTools\Component\Models\Exception\ModelsException;

try {
    $className = ModelRegistry::getResourceClass('InvalidVersion', 'Patient');
} catch (ModelsException $e) {
    echo "Models error: " . $e->getMessage();
}

// Specific exception methods
try {
    throw ModelsException::unsupportedVersion('R6');
} catch (ModelsException $e) {
    echo $e->getMessage(); // "Unsupported FHIR version: R6"
}

try {
    throw ModelsException::modelNotFound('NonExistentClass');
} catch (ModelsException $e) {
    echo $e->getMessage(); // "FHIR model class not found: NonExistentClass"
}
```

## Requirements

- **PHP**: 8.2 or higher
- **Dependencies**: None (minimal dependency footprint)

## Documentation

For detailed documentation, see:

- **Component Guide**: `/docs/component-guides/models.md`
- **Architecture Overview**: `/docs/architecture.md`
- **Migration Guide**: `/docs/migration-guide.md`

## License

This component is released under the MIT License. See the LICENSE file for details.
