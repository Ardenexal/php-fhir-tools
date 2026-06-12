# Serialization Component Guide

## Overview

The Serialization component converts FHIR model objects to and from JSON and XML. It can be used
standalone (no framework) or wired automatically through the FHIRBundle in a Symfony application.

The public surface is small and stable:

- `FHIRSerializationService` — the high-level entry point (`serializeToJson`, `serializeToXml`,
  `deserializeFromJson`, `deserializeFromXml`, `deserialize`).
- `FHIRSerializationContext` — an **immutable** value object describing serialization options.
- `Validator\FHIRValidator` — business-rule validation of model objects.

Everything else (the per-format normalizers, the type resolver, the metadata extractor) is an
internal detail that the service or the Symfony container wires for you. You normally never
instantiate those directly.

## Installation

### Standalone

```bash
composer require ardenexal/fhir-serialization ardenexal/fhir-models
```

`ardenexal/fhir-models` provides the generated R4/R4B/R5 model classes the serializer reads and
writes. Without it there is nothing to serialize.

### With FHIRBundle

The Serialization component is pulled in automatically with the bundle:

```bash
composer require ardenexal/fhir-bundle
```

## Basic Usage

### Creating the service

Outside a Symfony container, build a fully-wired service with the static factory. It defaults to
FHIR R4; pass a different `FhirVersion` for R4B or R5.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

$serializer = FHIRSerializationService::createDefault();           // R4
// $serializer = FHIRSerializationService::createDefault(FhirVersion::R5);
```

> **Do not** call `new FHIRSerializationService()` directly — the constructor requires a fully
> assembled `Serializer`, context factory, and debug-info collector. `createDefault()` (and
> `createWithIG()`, below) perform that wiring for you.

### Serialize and deserialize JSON

FHIR model classes use promoted public properties — set values via the constructor's named
arguments or by assigning the public property. There are no `setX()` setters.

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;

$patient = new PatientResource(
    id: 'example-123',
    active: true,
    name: [new HumanName(family: 'Doe', given: ['John'])],
);

// Serialize to JSON
$json = $serializer->serializeToJson($patient);
// {"resourceType":"Patient","id":"example-123","active":true,"name":[{"family":"Doe","given":["John"]}]}

// Deserialize JSON back to a model object
$restored = $serializer->deserializeFromJson($json, PatientResource::class);
echo $restored->id;                       // "example-123" (a plain string)
echo $restored->name[0]->family;          // "Doe"
```

> **Primitive typing on round-trip:** simple resource fields such as `id` stay plain PHP scalars,
> but typed primitive fields (e.g. `HumanName::$family`, `$given[]`) come back as
> `...\Primitive\StringPrimitive` objects after deserialization. These implement `Stringable`, so
> `echo`/string interpolation work directly — but a strict comparison needs a cast:
> `(string) $restored->name[0]->family === 'Doe'`.

### Serialize and deserialize XML

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

$patient = new PatientResource(id: 'example-123', active: true);

$xml = $serializer->serializeToXml($patient);
// <?xml version="1.0"?>
// <Patient xmlns="http://hl7.org/fhir"><id value="example-123"/><active value="true"/></Patient>

$restored = $serializer->deserializeFromXml($xml, PatientResource::class);
echo $restored->id; // "example-123"
```

XML deserialization strips `DOCTYPE` declarations (XXE protection) and preserves attribute values
as strings so numeric-looking primitives keep their precision on round-trip.

> **Known limitation (XML, single-element repeating fields):** a repeating element that contains
> exactly one value (for example a `HumanName` whose `given` is `['John']`) currently fails to
> deserialize back from XML, because XML collapses the lone element to a scalar. Two-or-more values
> (`given: ['John', 'James']`) round-trip correctly, as does omitting the field. JSON is unaffected.

### Auto-detecting format and resource type

`deserialize()` sniffs JSON vs XML from the payload and resolves the target class from the
`resourceType` (JSON) or root element (XML), so you can omit the target class:

```php
<?php

$resource = $serializer->deserialize($json);          // format + class auto-detected
$resource = $serializer->deserialize($xml, PatientResource::class); // explicit class
```

### With Symfony DI

When the FHIRBundle is installed, inject the service by type — the `fhir.serialization_service`
alias points at it:

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

final class PatientService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
    ) {}

    public function toJson(PatientResource $patient): string
    {
        return $this->serializer->serializeToJson($patient);
    }

    public function fromJson(string $json): PatientResource
    {
        return $this->serializer->deserializeFromJson($json, PatientResource::class);
    }
}
```

## Serialization Context

`FHIRSerializationContext` is an **immutable** value object. Start from a format factory
(`forJson()` / `forXml()`) and chain `with*()` calls — each returns a new instance. Pass the
result to a serialize/deserialize method via `toSymfonyContext()`.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;

$context = FHIRSerializationContext::forJson()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT)
    ->withUnknownElementPolicy(FHIRSerializationContext::UNKNOWN_POLICY_ERROR)
    ->withDebugInfo(true);

$json = $serializer->serializeToJson($patient, $context->toSymfonyContext());
```

### Available factories and modifiers

| Member | Effect |
|--------|--------|
| `FHIRSerializationContext::forJson()` | Base context for JSON. |
| `FHIRSerializationContext::forXml()` | Base context for XML. |
| `withValidationMode(string)` | `VALIDATION_STRICT` or `VALIDATION_LENIENT`. |
| `withUnknownElementPolicy(string)` | `UNKNOWN_POLICY_IGNORE`, `UNKNOWN_POLICY_ERROR`, or `UNKNOWN_POLICY_PRESERVE`. |
| `withFormat(string)` | `FORMAT_JSON` or `FORMAT_XML`. |
| `withDebugInfo(bool)` | Toggle debug-info collection. |
| `withPerformanceOptimization(bool)` | Skip non-essential validation for speed. |
| `withCustomOptions(array)` | Merge arbitrary Symfony serializer options. |
| `toSymfonyContext()` | Convert to the `array` accepted by the serialize/deserialize methods. |

There are also convenience constructors that bundle common combinations:
`withStrictValidation()`, `withLenientValidation()`, `withDebugging()`,
`preservingUnknownElements()`, `erroringOnUnknownElements()`.

After a serialize/deserialize call you can inspect what happened:

```php
<?php

$debug = $serializer->getDebugInfo(); // array<string, mixed>
```

## IG-Aware Serialization

To deserialize Implementation Guide extensions and profile subclasses into their typed PHP
classes, build the service with `createWithIG()`, pointing it at your generated IG output
directory and its PSR-4 namespace:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

$serializer = FHIRSerializationService::createWithIG(
    igOutputDirectory: '/app/src/FHIRIG',
    igNamespace: 'App\\FHIR\\IG',
    version: FhirVersion::R4B,
);
```

With no IG arguments, `createWithIG()` (and therefore `createDefault()`) still registers the base
model extensions, so standard typed extensions resolve out of the box. See the
[FHIRBundle guide](fhir-bundle.md) for generating IG classes with `fhir:generate-ig`.

## Validation

`Validator\FHIRValidator` checks a model object against its FHIR business rules. Its constructor
requires a `FHIRMetadataExtractor`, and `validate()` returns a **list of error message strings**
(empty when the object is valid).

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;

$validator = new FHIRValidator(new FHIRMetadataExtractor());

$errors = $validator->validate($patient); // array<string>
if ($errors !== []) {
    foreach ($errors as $message) {
        echo "Validation error: {$message}\n";
    }
}
```

To fail fast instead of collecting errors, use `validateOrThrow()`, which throws a
`ValidationException` when any rule fails:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;

try {
    $validator->validateOrThrow($patient);
} catch (ValidationException $e) {
    echo "Invalid: {$e->getMessage()}";
}
```

> For full resource conformance against profiles, terminology bindings, invariants, and
> Questionnaire/QuestionnaireResponse rules, use the dedicated **Validation** component
> (`ardenexal/fhir-validation`) and its `FHIRValidationService`, which produces an
> `OperationOutcome`. `FHIRValidator` covers structural/business-rule checks only.

## Error Handling

All serialize/deserialize failures are wrapped in `FHIRSerializationException`; validation
failures from `validateOrThrow()` raise `ValidationException`. Both extend the component base
`FHIRToolsException`.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

try {
    $patient = $serializer->deserializeFromJson($maybeInvalidJson, PatientResource::class);
} catch (FHIRSerializationException $e) {
    // Wraps the underlying decode/denormalize error; inspect $e->getPrevious() for detail.
    error_log('FHIR deserialization failed: ' . $e->getMessage());
}
```

## Testing

A round-trip assertion is the most useful unit test for the serializer:

```php
<?php

namespace App\Tests\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;
use PHPUnit\Framework\TestCase;

final class PatientRoundTripTest extends TestCase
{
    public function testJsonRoundTripPreservesData(): void
    {
        $serializer = FHIRSerializationService::createDefault();

        $patient = new PatientResource(
            id: 'rt-1',
            name: [new HumanName(family: 'Smith', given: ['Jane', 'Q'])],
        );

        $json     = $serializer->serializeToJson($patient);
        $restored = $serializer->deserializeFromJson($json, PatientResource::class);

        // `id` stays a plain string; primitive sub-fields return as Stringable objects, so cast.
        self::assertSame('rt-1', $restored->id);
        self::assertSame('Smith', (string) $restored->name[0]->family);
        self::assertSame(['Jane', 'Q'], array_map('strval', $restored->name[0]->given));
    }
}
```

In a Symfony test, fetch the configured service from the container instead of constructing it:

```php
<?php

self::bootKernel();
$serializer = static::getContainer()->get('fhir.serialization_service');
```

## Best Practices

- **Pick the right FHIR version once.** Build the service with the matching `FhirVersion` and
  reuse it; the model classes (`...\Models\R4`, `R4B`, `R5`) must match the service version.
- **Treat the context as immutable.** Capture the result of each `with*()` call; the original
  instance is unchanged.
- **Validate before persisting.** Run `FHIRValidator` (or the full Validation component) on
  inbound data rather than trusting deserialization to reject it.
- **Reuse the service.** `createDefault()` builds the full normalizer stack; construct it once
  and inject it, rather than per request.
