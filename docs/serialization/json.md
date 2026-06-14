---
description: Serialize and deserialize FHIR resources as JSON.
icon: brackets-curly
---

# JSON Serialization

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
echo $restored->id;              // "example-123" (a plain string)
echo $restored->name[0]->family; // "Doe"
```

`serializeToJson(object $fhirObject, array $context = []): string` and
`deserializeFromJson(string $jsonData, string $targetClass, array $context = []): object` both
accept an optional Symfony serializer context array — see [Context & Options](context.md) for
validation modes and unknown-element policies.

{% hint style="info" %}
**Primitive typing on round-trip:** simple resource fields such as `id` stay plain PHP scalars,
but typed primitive fields (e.g. `HumanName::$family`, `$given[]`) come back as
`...\Primitive\StringPrimitive` objects after deserialization. These implement `Stringable`, so
`echo` and string interpolation work directly — but a strict comparison needs a cast:
`(string) $restored->name[0]->family === 'Doe'`.
{% endhint %}

## Auto-detecting format and resource type

`deserialize(string $data, ?string $targetClass = null, array $context = [])` sniffs JSON vs XML
from the payload and resolves the target class from the `resourceType` (JSON) or root element
(XML), so you can omit the target class:

```php
<?php

$resource = $serializer->deserialize($json);          // format + class auto-detected
$resource = $serializer->deserialize($json, PatientResource::class); // explicit class
```

Class resolution delegates to the type resolver, so `meta.profile` and the IG type registry are
applied when available (see [IG-Aware Serialization](ig-aware.md)).

## Error handling

All serialize/deserialize failures are wrapped in `FHIRSerializationException` (which extends the
component base `FHIRToolsException`). The underlying decode/denormalize error is preserved as the
previous exception.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

try {
    $patient = $serializer->deserializeFromJson($maybeInvalidJson, PatientResource::class);
} catch (FHIRSerializationException $e) {
    // Inspect $e->getPrevious() for the underlying cause.
    error_log('FHIR deserialization failed: ' . $e->getMessage());
}
```

`FHIRSerializationException` also exposes `getElementPath()`, `getDetailedMessage()`, and (when
debug info was enabled) `getDebugInfo()` / `hasDebugInfo()`.
