# FHIR Serialization Component

Standalone PHP library for FHIR JSON serialization, deserialization, and validation.

## Installation

```bash
composer require ardenexal/fhir-serialization
```

## Usage

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

$service = new FHIRSerializationService(
    strictValidation: true,
    cacheMetadata: true
);

// Serialize FHIR resource to JSON
$json = $service->serialize($fhirResource);

// Deserialize JSON to FHIR resource
$resource = $service->deserialize($json, Patient::class);
```

## Features

- FHIR JSON serialization and deserialization
- Symfony Serializer integration
- FHIR validation capabilities
- High performance for large documents
- Metadata caching

## Requirements

- PHP 8.3+
- symfony/serializer ^6.4|^7.4
- symfony/validator ^6.4|^7.4