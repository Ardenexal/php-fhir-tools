---
description: Inject and use FHIR Tools services in a Symfony application.
icon: diagram-project
---

# Services & Dependency Injection

The bundle registers the serialization, validation, code generation, and FHIRPath services for
autowiring.

```php
// public function __construct(private FHIRSerializationService $service) {}
```

<!-- TODO: migrate "Registered Services" / "Using Services" + controller example from
     src/Bundle/FHIRBundle/README.md and docs/component-guides/fhir-bundle.md -->

## Controller example

<!-- TODO: migrate the controller deserialize/serialize/error-handling example -->

## Testing with bundle services

<!-- TODO: migrate "Testing with Bundle Services" -->

<!-- MIGRATION SOURCE: bundle README + guide (Registered Services, Using Services, Testing) -->
