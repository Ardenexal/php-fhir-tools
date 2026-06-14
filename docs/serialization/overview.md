---
description: Convert FHIR model objects to and from JSON and XML.
icon: arrow-right-arrow-left
---

# Overview

The Serialization component converts FHIR model objects to and from JSON and XML. It works standalone
(no framework) or wired through the [Symfony Bundle](../bundle/configuration.md). It is built on top
of the Symfony Serializer component, with FHIR-aware normalizers handling resources, complex types,
backbone elements, and primitives.

The public surface is small and stable:

* `FHIRSerializationService` — the high-level entry point (`serializeToJson`, `serializeToXml`,
  `deserializeFromJson`, `deserializeFromXml`, `deserialize`).
* `FHIRSerializationContext` — an immutable value object describing options. See [Context & Options](context.md).
* `Validator\FHIRValidator` — business-rule validation. See [Validation](../validation/overview.md).

Everything else (the per-format normalizers, the type resolver, the metadata extractor) is an
internal detail that the service or the Symfony container wires for you. You normally never
instantiate those directly.

## Installation

{% tabs %}
{% tab title="Standalone" %}
```bash
composer require ardenexal/fhir-serialization ardenexal/fhir-models
```

`ardenexal/fhir-models` provides the generated R4/R4B/R5 model classes the serializer reads and
writes. Without it there is nothing to serialize.
{% endtab %}

{% tab title="With FHIRBundle" %}
The Serialization component is pulled in automatically with the bundle:

```bash
composer require ardenexal/fhir-bundle
```
{% endtab %}
{% endtabs %}

## Creating the service

{% tabs %}
{% tab title="Standalone" %}
Outside a Symfony container, build a fully-wired service with the static factory. It defaults to
FHIR R4; pass a different `FhirVersion` for R4B or R5.

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

$serializer = FHIRSerializationService::createDefault();                 // R4
// $serializer = FHIRSerializationService::createDefault(FhirVersion::R5);
```

{% hint style="warning" %}
**Do not** call `new FHIRSerializationService()` directly — the constructor requires a fully
assembled `Serializer`, context factory, and debug-info collector. `createDefault()` (and
`createWithIG()`, see [IG-Aware](ig-aware.md)) perform that wiring for you.
{% endhint %}
{% endtab %}

{% tab title="Symfony DI" %}
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
}
```
{% endtab %}
{% endtabs %}

## Next

* [JSON Serialization](json.md)
* [XML Serialization](xml.md)
* [Context & Options](context.md)
* [IG-Aware Serialization](ig-aware.md)
* [Round-Trip Testing](round-trip.md)
