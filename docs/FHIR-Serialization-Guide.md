# FHIR Serialization Guide

This guide explains how to use the FHIR serialization system in PHP FHIRTools to convert between FHIR objects and JSON/XML formats.

## Overview

The FHIR serialization system provides:
- **JSON and XML serialization** following official FHIR specifications
- **Configurable validation modes** (strict/lenient)
- **Flexible unknown element handling** (ignore/error/preserve)
- **Performance optimization options**
- **Debug information support**
- **Extension handling** with underscore notation for JSON
- **Round-trip serialization** preserving all FHIR data

## Quick Start

### Basic Usage with Symfony Serializer

```php
use Symfony\Component\Serializer\Serializer;
use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataCache;

// Set up the serializer with FHIR normalizers
$metadataCache = new FHIRMetadataCache();
$metadataExtractor = new FHIRMetadataExtractor($metadataCache);
$typeResolver = new FHIRTypeResolver();

$normalizers = [
    new FHIRResourceNormalizer($metadataExtractor, $typeResolver),
    // Add other FHIR normalizers as needed
];

$serializer = new Serializer($normalizers);

// Serialize a FHIR resource to JSON
$patient = new FHIRPatient();
$patient->id = 'patient-123';
$patient->resourceType = 'Patient';

$json = $serializer->serialize($patient, 'json');
echo $json;

// Deserialize JSON back to FHIR object
$deserializedPatient = $serializer->deserialize($json, FHIRPatient::class, 'json');
```

## Configuration System

### FHIRSerializationContext

The `FHIRSerializationContext` class provides comprehensive configuration options:

```php
use Ardenexal\FHIRTools\Serialization\FHIRSerializationContext;

// Create context with default settings (JSON, lenient validation)
$context = new FHIRSerializationContext();

// Create context for XML with strict validation
$context = FHIRSerializationContext::forXml()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);

// Create context with debugging enabled
$context = FHIRSerializationContext::withDebugging();

// Create context that preserves unknown elements
$context = FHIRSerializationContext::preservingUnknownElements();
```

### Configuration Options

#### Format Selection

```php
// JSON format (default)
$context = FHIRSerializationContext::forJson();

// XML format
$context = FHIRSerializationContext::forXml();

// Switch formats
$xmlContext = $jsonContext->withFormat(FHIRSerializationContext::FORMAT_XML);
```

#### Validation Modes

```php
// Strict validation (enhanced error reporting)
$context = FHIRSerializationContext::withStrictValidation();

// Lenient validation (backward compatibility)
$context = FHIRSerializationContext::withLenientValidation();

// Switch validation modes
$strictContext = $context->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);
```

#### Unknown Element Policies

```php
// Ignore unknown elements (default)
$context = new FHIRSerializationContext(
    unknownElementPolicy: FHIRSerializationContext::UNKNOWN_POLICY_IGNORE
);

// Error on unknown elements
$context = FHIRSerializationContext::erroringOnUnknownElements();

// Preserve unknown elements
$context = FHIRSerializationContext::preservingUnknownElements();
```

#### Performance Optimization

```php
// Enable performance optimizations
$context = $context->withPerformanceOptimization(true);

// Disable reference validation for better performance
$context = new FHIRSerializationContext(
    skipNonEssentialValidation: true,
    validateReferences: false
);
```

#### Debug Information

```php
// Enable debug information
$context = $context->withDebugInfo(true);

// Add custom debug options
$context = $context->withCustomOptions([
    'debug_level' => 'verbose',
    'trace_normalization' => true
]);
```

## Using Context with Serializer

### Method 1: Convert to Symfony Context

```php
$fhirContext = FHIRSerializationContext::forXml()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT)
    ->withDebugInfo(true);

$symfonyContext = $fhirContext->toSymfonyContext();

// Serialize with context
$xml = $serializer->serialize($patient, 'xml', $symfonyContext);

// Deserialize with context
$patient = $serializer->deserialize($xml, FHIRPatient::class, 'xml', $symfonyContext);
```

### Method 2: Direct Context Usage

```php
// The normalizers automatically detect and use FHIRSerializationContext
$context = [
    'fhir_format' => 'xml',
    'fhir_validation_mode' => 'strict',
    'fhir_enable_debug_info' => true,
    'fhir_unknown_element_policy' => 'preserve'
];

$result = $serializer->serialize($patient, 'xml', $context);
```

## Format-Specific Features

### JSON Serialization

```php
$context = FHIRSerializationContext::forJson();

// Features automatically handled:
// - resourceType field inclusion
// - Primitive extension underscore notation
// - Sparse extension arrays
// - Null value omission
// - FHIR JSON specification compliance
```

#### JSON with Extensions

```php
// FHIR primitive with extensions
$name = new FHIRString();
$name->value = 'John Doe';
$name->extension = [
    new FHIRExtension('http://example.org/nickname', 'Johnny')
];

// Serializes to:
// {
//   "name": "John Doe",
//   "_name": {
//     "extension": [
//       {
//         "url": "http://example.org/nickname",
//         "valueString": "Johnny"
//       }
//     ]
//   }
// }
```

### XML Serialization

```php
$context = FHIRSerializationContext::forXml()
    ->withCustomOptions(['enable_xml_namespaces' => true]);

// Features automatically handled:
// - FHIR namespace declarations
// - XML primitive extension attributes
// - Proper XML element structure
// - FHIR XML specification compliance
```

#### XML with Namespaces

```php
// Enable XML namespaces (default for XML)
$context = FHIRSerializationContext::forXml();

// Disable XML namespaces if needed
$context = new FHIRSerializationContext(
    format: FHIRSerializationContext::FORMAT_XML,
    enableXmlNamespaces: false
);
```

## Error Handling

### Exception Types

```php
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

try {
    $result = $serializer->serialize($invalidObject, 'json');
} catch (FHIRSerializationException $e) {
    // Enhanced error information
    echo "Error: " . $e->getMessage();
    echo "Element path: " . $e->getElementPath();
    echo "Context: " . json_encode($e->getContext());
    
    if ($e->hasDebugInfo()) {
        echo "Debug info: " . $e->getDebugInfo()->getSummary();
    }
}
```

### Validation Errors

```php
// Strict validation throws detailed exceptions
$context = FHIRSerializationContext::withStrictValidation();

try {
    $patient = $serializer->deserialize($invalidJson, FHIRPatient::class, 'json', $context->toSymfonyContext());
} catch (FHIRSerializationException $e) {
    if ($e->getElementPath()) {
        echo "Validation error at: " . $e->getElementPath();
    }
}
```

### Unknown Element Handling

```php
// Error on unknown elements
$context = FHIRSerializationContext::erroringOnUnknownElements();

try {
    $patient = $serializer->deserialize($jsonWithUnknownFields, FHIRPatient::class, 'json', $context->toSymfonyContext());
} catch (FHIRSerializationException $e) {
    echo "Unknown element encountered: " . $e->getMessage();
}

// Preserve unknown elements
$context = FHIRSerializationContext::preservingUnknownElements();
$patient = $serializer->deserialize($jsonWithUnknownFields, FHIRPatient::class, 'json', $context->toSymfonyContext());
// Unknown fields are preserved in the object
```

## Advanced Usage

### Custom Normalizers

```php
// Add custom normalizers for specific FHIR types
$normalizers = [
    new FHIRResourceNormalizer($metadataExtractor, $typeResolver),
    new FHIRComplexTypeNormalizer($metadataExtractor, $typeResolver),
    new FHIRPrimitiveTypeNormalizer($metadataExtractor, $typeResolver),
    new FHIRBackboneElementNormalizer($metadataExtractor, $typeResolver),
    // Your custom normalizers
];

$serializer = new Serializer($normalizers);
```

### Performance Optimization

```php
// High-performance context for bulk operations
$context = FHIRSerializationContext::withLenientValidation()
    ->withPerformanceOptimization(true)
    ->withCustomOptions([
        'skip_validation' => true,
        'omit_null_values' => true,
        'omit_empty_arrays' => true
    ]);

// Process many resources efficiently
foreach ($resources as $resource) {
    $json = $serializer->serialize($resource, 'json', $context->toSymfonyContext());
    // Process JSON...
}
```

### Debug Information

```php
$context = FHIRSerializationContext::withDebugging()
    ->withCustomOptions(['debug_level' => 'verbose']);

$result = $serializer->serialize($patient, 'json', $context->toSymfonyContext());

// Access debug information
if (isset($context->toSymfonyContext()['fhir_debug_info'])) {
    $debugInfo = $context->toSymfonyContext()['fhir_debug_info'];
    echo "Operation took: " . $debugInfo->getDurationMs() . "ms";
    echo "Warnings: " . $debugInfo->getWarningCount();
}
```

### Round-Trip Validation

```php
// Ensure data integrity through serialization cycles
$originalPatient = new FHIRPatient();
// ... populate patient data

// Serialize to JSON
$json = $serializer->serialize($originalPatient, 'json');

// Deserialize back to object
$deserializedPatient = $serializer->deserialize($json, FHIRPatient::class, 'json');

// Verify round-trip integrity
assert($originalPatient->id === $deserializedPatient->id);
assert($originalPatient->resourceType === $deserializedPatient->resourceType);
```

## Best Practices

### 1. Context Reuse

```php
// Create context once and reuse
$context = FHIRSerializationContext::forJson()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);

$symfonyContext = $context->toSymfonyContext();

// Reuse for multiple operations
$json1 = $serializer->serialize($patient1, 'json', $symfonyContext);
$json2 = $serializer->serialize($patient2, 'json', $symfonyContext);
```

### 2. Error Handling Strategy

```php
function serializeFHIRResource($resource, string $format = 'json'): string
{
    $context = FHIRSerializationContext::withStrictValidation()
        ->withFormat($format);
    
    try {
        return $this->serializer->serialize($resource, $format, $context->toSymfonyContext());
    } catch (FHIRSerializationException $e) {
        // Log detailed error information
        $this->logger->error('FHIR serialization failed', [
            'element_path' => $e->getElementPath(),
            'context' => $e->getContext(),
            'message' => $e->getMessage()
        ]);
        
        throw $e;
    }
}
```

### 3. Performance Monitoring

```php
$context = FHIRSerializationContext::withDebugging();

$startTime = microtime(true);
$result = $serializer->serialize($resource, 'json', $context->toSymfonyContext());
$endTime = microtime(true);

$debugInfo = $context->toSymfonyContext()['fhir_debug_info'] ?? null;
if ($debugInfo) {
    echo "Serialization took: " . $debugInfo->getDurationMs() . "ms";
    if ($debugInfo->hasWarnings()) {
        echo "Warnings encountered: " . implode(', ', $debugInfo->warnings);
    }
}
```

### 4. Validation Strategies

```php
// Development: Use strict validation
if ($this->environment === 'development') {
    $context = FHIRSerializationContext::withStrictValidation()
        ->withDebugInfo(true);
}

// Production: Use lenient validation for performance
else {
    $context = FHIRSerializationContext::withLenientValidation()
        ->withPerformanceOptimization(true);
}
```

## Migration from Legacy Code

### Before (Basic Symfony Serializer)

```php
// Old way
$serializer = new Serializer([new ObjectNormalizer()]);
$json = $serializer->serialize($patient, 'json');
```

### After (FHIR-Aware Serialization)

```php
// New way with FHIR-specific features
$normalizers = [
    new FHIRResourceNormalizer($metadataExtractor, $typeResolver),
];
$serializer = new Serializer($normalizers);

$context = FHIRSerializationContext::forJson();
$json = $serializer->serialize($patient, 'json', $context->toSymfonyContext());
```

## Troubleshooting

### Common Issues

1. **Missing resourceType**: Ensure FHIR resources have the resourceType field set
2. **Extension serialization**: Use proper FHIR extension objects, not plain arrays
3. **Unknown elements**: Configure the unknown element policy appropriately
4. **Performance**: Use lenient validation and performance optimizations for bulk operations
5. **XML namespaces**: Enable XML namespaces for proper FHIR XML compliance

### Debug Tips

```php
// Enable maximum debugging
$context = FHIRSerializationContext::withDebugging()
    ->withCustomOptions([
        'debug_level' => 'verbose',
        'trace_operations' => true,
        'log_warnings' => true
    ]);

// Check for validation issues
$context = FHIRSerializationContext::withStrictValidation();

// Preserve unknown elements to see what's being ignored
$context = FHIRSerializationContext::preservingUnknownElements();
```

## API Reference

For detailed API documentation, see:
- `FHIRSerializationContext` - Configuration options
- `FHIRSerializationException` - Error handling
- `FHIRSerializationDebugInfo` - Debug information
- `FHIRResourceNormalizer` - Resource serialization
- `FHIRComplexTypeNormalizer` - Complex type serialization
- `FHIRPrimitiveTypeNormalizer` - Primitive type serialization
- `FHIRBackboneElementNormalizer` - Backbone element serialization
