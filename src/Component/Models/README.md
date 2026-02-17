# FHIR Models Component

Generated PHP model classes for FHIR resources, data types, primitives, and enums. These classes are produced by the [CodeGeneration](../CodeGeneration/README.md) component from FHIR StructureDefinitions.

## Generating Models

Models are not committed for all FHIR versions by default. Generate them with:

```bash
# Generate R4 models
composer run generate-models-r4

# Generate R4B models
composer run generate-models-r4b

# Generate all versions (R4, R4B, R5)
composer run generate-models-all
```

## Namespace Organization

Generated models are organized in version-specific namespaces:

```
Ardenexal\FHIRTools\Component\Models\
├── R4\
│   ├── Resource\          # FHIR Resources (FHIRPatient, FHIRObservation, etc.)
│   ├── DataType\          # Complex Data Types (FHIRHumanName, FHIRAddress, etc.)
│   ├── Primitive\         # Primitive Types (FHIRString, FHIRInteger, etc.)
│   └── Enum\              # Value Set Enums (FHIRAdministrativeGender, etc.)
├── R4B\                   # Same structure as R4
└── R5\                    # Same structure as R4
```

Backbone elements are nested within their parent resource directory:

```
R4\Resource\
├── FHIRPatient.php
├── Patient\
│   ├── FHIRPatientContact.php
│   ├── FHIRPatientCommunication.php
│   └── FHIRPatientLink.php
```

## Usage

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPatient;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdministrativeGender;

// Create a patient
$patient = new FHIRPatient(
    id: new FHIRString('patient-123'),
    name: [
        new FHIRHumanName(
            family: new FHIRString('Doe'),
            given: [new FHIRString('John')],
        ),
    ],
);

// Access properties
echo $patient->id->value;           // 'patient-123'
echo $patient->name[0]->family->value; // 'Doe'

// Enums
$gender = FHIRAdministrativeGender::MALE;
$cases = FHIRAdministrativeGender::cases(); // all enum values
```

## Version Isolation

Each FHIR version has its own namespace, so multiple versions can coexist:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPatient as R4Patient;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPatient as R4BPatient;
```

## Requirements

- **PHP**: 8.3 or higher

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
