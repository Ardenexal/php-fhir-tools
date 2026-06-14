---
description: Full reference for the bundle's fhir configuration keys.
icon: list-tree
---

# Configuration Reference

Complete reference for every key under the `fhir` configuration root, as defined by
`Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Configuration`. All keys are optional;
each row lists the type, default, and the container parameter (where applicable) the value is bound
to. For setup guidance see [Bundle → Installation & Configuration](../bundle/configuration.md).

{% hint style="info" %}
Configuration is validated. An unknown key (or an unsupported `default_version`) causes the
container to fail to build with an `Invalid configuration for path "fhir.…"` error.
{% endhint %}

## Root keys

| Key | Type | Default | Container parameter |
| --- | --- | --- | --- |
| `output_directory` | string | `%kernel.project_dir%/output` | `fhir.output_directory` |
| `cache_directory` | string | `%kernel.cache_dir%/fhir` | `fhir.cache_directory` |
| `default_version` | string | `R4` | `fhir.default_version` |

- `output_directory` — directory where generated FHIR classes are stored.
- `cache_directory` — directory for FHIR package cache and metadata.
- `default_version` — default FHIR version when not specified. Must be one of `R4`, `R4B`, `R5`;
  any other value is rejected during validation.

## `validation`

| Key | Type | Default | Notes |
| --- | --- | --- | --- |
| `validation.enabled` | bool | `true` | Bound to `fhir.validation_enabled`. Enable validation during code generation. |
| `validation.strict_mode` | bool | `false` | Bound to `fhir.validation_strict_mode`. Fail on warnings, not just errors. |
| `validation.message_overrides` | map<string,string> | `[]` | Keys are simple constraint class names (e.g. `FHIRFixedValue`), values are message templates. |
| `validation.terminology_cache_pool` | string\|null | `null` | PSR-6 cache pool service ID for terminology results. `null` disables caching. |
| `validation.terminology_cache_ttl` | int | `3600` | TTL in seconds for cached terminology results. `0` = no expiry. Minimum `0`. |

When `message_overrides` is non-empty, each entry is wired into
`FHIRValidationMessageRegistry::setOverride()`. When `terminology_cache_pool` is set, the pool is
aliased as `fhir.terminology_cache` and `CachingFHIRTerminologyClient` is registered as a decorator
over `FHIRTerminologyClientInterface`.

## `serialization`

| Key | Type | Default | Notes |
| --- | --- | --- | --- |
| `serialization.metadata_cache_pool` | string\|null | `cache.app` | Bound to `fhir.serialization.metadata_cache_pool`. PSR-6 pool for property metadata. `null` disables persistent caching. |
| `serialization.enable_cache_warmer` | bool | `false` | Register a `kernel.cache_warmer` to pre-populate metadata during `cache:warmup`. Only takes effect when `metadata_cache_pool` is set. |

When `metadata_cache_pool` is set, it is aliased as `fhir.metadata_cache` and injected into
`PropertyMetadataProvider`'s `$psrCache` argument. The `FHIRMetadataCacheWarmer` is registered only
when `enable_cache_warmer` is `true`.

## `path`

| Key | Type | Default | Notes |
| --- | --- | --- | --- |
| `path.cache_size` | int | `100` | Bound to `fhir.path.cache_size`. Max number of FHIRPath expressions to cache. Min `10`, max `10000`. |

## `packages`

A map keyed by package name. Each entry:

| Key | Type | Default | Notes |
| --- | --- | --- | --- |
| `packages.<name>.version` | string | — | **Required.** Package version to use. |
| `packages.<name>.url` | string | — | Optional custom package URL. |
| `packages.<name>.auto_update` | bool | `false` | Automatically update package to the latest version. |

```yaml
fhir:
    packages:
        hl7.fhir.r4b.core:
            version: '4.3.0'
            auto_update: false
```

## `ig`

Implementation Guide (IG) code generation settings. Used by [`fhir:generate-ig`](commands.md#fhir-generate-ig).

| Key | Type | Default | Container parameter |
| --- | --- | --- | --- |
| `ig.packages` | list<string> | `[]` | `fhir.ig.packages` |
| `ig.offline` | bool | `false` | `fhir.ig.offline` |
| `ig.output_directory` | string\|null | `null` | `fhir.ig.output_directory` |
| `ig.namespace` | string\|null | `null` | `fhir.ig.namespace` |

- `ig.packages` — IG packages to generate, in dependency order (e.g. `au.base` before `au.core`).
  Accepts `name` or `name#version` format.
- `ig.offline` — use only locally cached packages; skip network downloads.
- `ig.output_directory` — root directory for generated IG PHP classes. Defaults to
  `output_directory/IG` when `null`. Must match the PSR-4 source root for `ig.namespace`.
- `ig.namespace` — root PHP namespace for generated IG classes (e.g. `App\FHIR\IG`). Must match the
  PSR-4 autoload mapping in `composer.json`. Defaults to the library-internal namespace
  (`Ardenexal\FHIRTools\Component\Models\IG`) when `null`.
