---
description: Serialize typed extension and profile classes from Implementation Guides.
icon: sitemap
---

# IG-Aware Serialization

When you generate typed classes for an [Implementation Guide](../code-generation/implementation-guides.md),
the serializer must be IG-aware to deserialize those typed extensions and profile subclasses into
their PHP classes (rather than the base `Extension` type). Build the service with `createWithIG()`,
pointing it at your generated IG output directory and its PSR-4 namespace:

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

`createWithIG()` scans the base model `Extension` directories plus the optional IG output directory,
building an IG type registry that enables typed extension deserialization, `meta.profile` URL
resolution, and discriminator-based slice resolution.

{% hint style="info" %}
With no IG arguments, `createWithIG()` (and therefore `createDefault()`, which delegates to it)
still registers the base model extensions, so standard typed extensions resolve out of the box.
Pass an empty string for `igOutputDirectory` / `igNamespace` to skip IG scanning.
{% endhint %}

The signature is:

```php
public static function createWithIG(
    string $igOutputDirectory = '',
    string $igNamespace = '',
    FhirVersion $version = FhirVersion::R4
): self
```

See the [FHIRBundle guide](../bundle/configuration.md) for generating IG classes with
`fhir:generate-ig`.
