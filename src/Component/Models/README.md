# FHIR Models Component

Generated PHP model classes for FHIR resources, data types, primitives, and enums. These classes are produced by the [CodeGeneration](../CodeGeneration/README.md) component from FHIR StructureDefinitions.

## Generating Models

Models are not committed for all FHIR versions by default. Generate them with:

```bash
# Generate R4 models
composer run generate-models-r4

# Generate R4B models
composer run generate-models-r4b

# Generate R5 models
composer run generate-models-r5

# Generate all versions (R4, R4B, R5)
composer run generate-models-all
```

## Namespace Organization

Generated models are organized in version-specific namespaces:

```
Ardenexal\FHIRTools\Component\Models\
├── R4\
│   ├── Resource\          # FHIR Resources (PatientResource, ObservationResource, etc.)
│   ├── DataType\          # Complex Data Types (HumanName, Address, etc.)
│   ├── Primitive\         # Primitive Types (StringPrimitive, IntegerPrimitive, etc.)
│   └── Enum\              # Value Set Enums (AdministrativeGender, etc.)
├── R4B\                   # Same structure as R4
└── R5\                    # Same structure as R4 (with Base and DataType intermediate types)
```

Backbone elements are nested within their parent resource directory:

```
R4\Resource\
├── PatientResource.php
├── Patient\
│   ├── PatientContact.php
│   ├── PatientCommunication.php
│   └── PatientLink.php
```

## Usage

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdministrativeGender;

// Create a patient
$patient = new PatientResource(
    name: [
        new HumanName(
            family: new StringPrimitive(value: 'Doe'),
            given: [new StringPrimitive(value: 'John')],
        ),
    ],
);

// Access properties
echo $patient->name[0]->family->value; // 'Doe'

// Enums
$gender = AdministrativeGender::Male;
$cases = AdministrativeGender::cases(); // all enum values
```

## Version Isolation

Each FHIR version has its own namespace, so multiple versions can coexist:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource as R4Patient;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource as R4BPatient;
```

## Requirements

- **PHP**: 8.3 or higher

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
