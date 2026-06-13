---
description: Convert FHIR model objects to and from JSON and XML.
icon: arrow-right-arrow-left
---

# Overview

The Serialization component converts FHIR model objects to and from JSON and XML. It works standalone
(no framework) or wired through the [Symfony Bundle](../bundle/configuration.md).

The public surface is small and stable:

* `FHIRSerializationService` — the high-level entry point.
* `FHIRSerializationContext` — an immutable value object describing options. See [Context & Options](context.md).
* `Validator\FHIRValidator` — business-rule validation. See [Validation](../validation/overview.md).

## Creating the service

{% tabs %}
{% tab title="Standalone" %}
<!-- TODO: migrate createDefault() example from docs/component-guides/serialization.md -->
```php
// $service = FHIRSerializationService::createDefault();
```
{% endtab %}

{% tab title="Symfony DI" %}
<!-- TODO: migrate the DI injection example -->
```php
// public function __construct(private FHIRSerializationService $service) {}
```
{% endtab %}
{% endtabs %}

## Next

* [JSON Serialization](json.md)
* [XML Serialization](xml.md)
* [IG-Aware Serialization](ig-aware.md)

<!-- MIGRATION SOURCE: src/Component/Serialization/README.md + docs/component-guides/serialization.md
     (merge the two overlapping sources; this page is canonical) -->
