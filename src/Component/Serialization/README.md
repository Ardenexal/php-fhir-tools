# FHIR Serialization Component

PHP library for FHIR JSON and XML serialization and deserialization, built on top of the Symfony Serializer component.

## Features

- FHIR-compliant JSON and XML serialization
- Configurable validation modes (strict/lenient/performance)
- Auto-detection of format and resource type
- Round-trip serialization testing
- Debug information support

## Quick Start

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

// Serialize to JSON
$json = $service->serializeToJson($patient);

// Serialize to XML
$xml = $service->serializeToXml($patient);

// Deserialize from JSON
$patient = $service->deserializeFromJson($json, FHIRPatient::class);

// Deserialize from XML
$patient = $service->deserializeFromXml($xml, FHIRPatient::class);

// Auto-detect format and resource type
$resource = $service->deserialize($data);
```

## Serialization Contexts

The `FHIRSerializationContextFactory` provides pre-configured contexts:

```php
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;

$factory = new FHIRSerializationContextFactory();

// Default contexts
$jsonContext = $factory->createJsonContext();
$xmlContext = $factory->createXmlContext();

// Strict validation for production
$strictContext = $factory->createStrictContext('json');

// Lenient for development
$lenientContext = $factory->createLenientContext('json');

// Performance-optimized (minimal validation)
$perfContext = $factory->createPerformanceContext('json');

// Debug mode with detailed information
$debugContext = $factory->createDebugContext('json');
```

### Context Options

All contexts support these FHIR-specific options:

| Option | Default | Description |
|--------|---------|-------------|
| `fhir_strict_validation` | `true` | Enable strict FHIR validation |
| `fhir_include_extensions` | `true` | Include FHIR extensions |
| `fhir_include_metadata` | `true` | Include metadata elements |
| `fhir_unknown_element_policy` | `ignore` | How to handle unknown elements (`ignore`, `error`, `preserve`) |
| `fhir_validate_references` | `false` | Validate FHIR references |

Pass custom overrides to any factory method:

```php
$context = $factory->createJsonContext([
    'fhir_strict_validation' => false,
    'max_depth' => 20,
]);
```

## Round-Trip Testing

Verify serialization integrity:

```php
// Serialize then deserialize, returns the deserialized object
$roundTripped = $service->roundTripTest($patient, 'json');
$roundTripped = $service->roundTripTest($patient, 'xml');
```

## Error Handling

```php
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

try {
    $patient = $service->deserializeFromJson($invalidJson, FHIRPatient::class);
} catch (FHIRSerializationException $e) {
    echo "Serialization error: {$e->getMessage()}";
}
```

## Symfony Integration

When used with the FHIRBundle, the service is automatically registered and available via dependency injection:

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

class PatientService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
    ) {}

    public function processPatientJson(string $json): object
    {
        return $this->serializer->deserializeFromJson($json, FHIRPatient::class);
    }
}
```

## Requirements

- **PHP**: 8.3 or higher

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
