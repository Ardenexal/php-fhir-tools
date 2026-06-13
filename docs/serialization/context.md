---
description: Configure serialization with the immutable FHIRSerializationContext.
icon: sliders
---

# Serialization Context & Options

`FHIRSerializationContext` is an immutable, chainable value object that configures how serialization
behaves — validation mode, unknown-element policy, debug info, and more.

```php
// $context = FHIRSerializationContext::forJson()->withValidationMode(...);
```

## Validation modes

<!-- TODO: migrate strict / lenient / performance modes -->

## Unknown-element policies

<!-- TODO: migrate ignore / error / preserve policies -->

<!-- MIGRATION SOURCE: serialization README/guide (Serialization Contexts, Context Options) -->
