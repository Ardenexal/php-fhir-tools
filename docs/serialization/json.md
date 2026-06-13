---
description: Serialize and deserialize FHIR resources as JSON.
icon: brackets-curly
---

# JSON Serialization

<!-- TODO: migrate "Serialize and deserialize JSON" + "Auto-detecting format and resource type"
     from docs/component-guides/serialization.md -->

```php
// $json = $service->serializeToJson($patient);
// $patient = $service->deserializeFromJson($json, FHIRPatient::class);
```

See [Error Handling](#error-handling) and [Context & Options](context.md) for validation modes.

## Error handling

<!-- TODO: migrate FHIRSerializationException notes -->

<!-- MIGRATION SOURCE: serialization README/guide (JSON sections) -->
