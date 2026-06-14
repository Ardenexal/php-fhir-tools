---
description: Configure serialization with the immutable FHIRSerializationContext.
icon: sliders
---

# Serialization Context & Options

`FHIRSerializationContext` is an immutable, chainable value object that configures how serialization
behaves — validation mode, unknown-element policy, debug info, and more. Start from a format factory
(`forJson()` / `forXml()`), chain `with*()` calls (each returns a new instance), then pass the
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

{% hint style="info" %}
The context is immutable — every `with*()` call returns a **new** instance and leaves the original
unchanged. Always capture the return value.
{% endhint %}

## Factories and modifiers

| Member | Effect |
|--------|--------|
| `FHIRSerializationContext::forJson()` | Base context for JSON. |
| `FHIRSerializationContext::forXml()` | Base context for XML (XML namespaces enabled). |
| `withFormat(string)` | `FORMAT_JSON` or `FORMAT_XML`. |
| `withValidationMode(string)` | `VALIDATION_STRICT` or `VALIDATION_LENIENT`. |
| `withUnknownElementPolicy(string)` | `UNKNOWN_POLICY_IGNORE`, `UNKNOWN_POLICY_ERROR`, or `UNKNOWN_POLICY_PRESERVE`. |
| `withDebugInfo(bool)` | Toggle debug-info collection. |
| `withPerformanceOptimization(bool)` | Skip non-essential validation for speed. |
| `withCustomOptions(array)` | Merge arbitrary Symfony serializer options. |
| `toSymfonyContext()` | Convert to the `array` accepted by the serialize/deserialize methods. |

Convenience constructors bundle common combinations: `withStrictValidation()`,
`withLenientValidation()`, `withDebugging()`, `preservingUnknownElements()`,
`erroringOnUnknownElements()`.

## Validation modes

The constructor default is **lenient** (`VALIDATION_LENIENT`). The mode is validated on
construction — an invalid value throws `\InvalidArgumentException`.

| Mode | Constant | Behavior |
|------|----------|----------|
| Strict | `VALIDATION_STRICT` | Full FHIR validation; `withStrictValidation()` also enables reference validation and does not skip non-essential checks. |
| Lenient | `VALIDATION_LENIENT` | Relaxed validation for development/performance; `withLenientValidation()` skips non-essential checks and disables reference validation. |

```php
<?php

$strict = FHIRSerializationContext::forJson()->withStrictValidation();
$lenient = FHIRSerializationContext::forJson()->withLenientValidation();
```

For raw, performance-tuned context arrays there is also `FHIRSerializationContextFactory`
(`createJsonContext`, `createXmlContext`, `createStrictContext`, `createLenientContext`,
`createPerformanceContext`, `createDebugContext`), each accepting an `$overrides` array. The
service uses this factory internally; you rarely call it directly.

## Unknown-element policies

Controls what happens when an element in the payload is not recognised. Default is
`UNKNOWN_POLICY_IGNORE`.

| Policy | Constant | Behavior |
|--------|----------|----------|
| Ignore | `UNKNOWN_POLICY_IGNORE` | Silently drop unknown elements (default). |
| Error | `UNKNOWN_POLICY_ERROR` | Reject unknown elements. |
| Preserve | `UNKNOWN_POLICY_PRESERVE` | Keep unknown elements through the round-trip. |

```php
<?php

$preserving = FHIRSerializationContext::forJson()->preservingUnknownElements();
$erroring   = FHIRSerializationContext::forJson()->erroringOnUnknownElements();
```

## Inspecting the last operation

After a serialize/deserialize call you can inspect what happened:

```php
<?php

$debug = $serializer->getDebugInfo(); // array<string, mixed>
```
