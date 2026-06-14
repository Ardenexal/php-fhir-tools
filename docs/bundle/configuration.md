---
description: Install and configure the FHIR Symfony bundle.
icon: gear
---

# Installation & Configuration

The bundle (`Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle`) registers all FHIR Tools services
automatically in a Symfony application: serialization, validation, code generation, and FHIRPath.

## Install

{% tabs %}
{% tab title="Automatic (Flex)" %}
With Symfony Flex installed, the [Flex recipe](flex-recipe.md) registers the bundle and writes the
config files for you:

```bash
composer require ardenexal/fhir-bundle
```
{% endtab %}

{% tab title="Manual" %}
Install without recipes, then register the bundle in `config/bundles.php`:

```bash
composer require ardenexal/fhir-bundle --no-recipes
```

```php
// config/bundles.php
return [
    // ...
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

{% hint style="warning" %}
The bundle class is `Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle` — there is **no** `\src`
segment in the namespace even though the file lives under `src/`.
{% endhint %}
{% endtab %}
{% endtabs %}

## Configuration

All keys under the `fhir` root are optional. The tree below lists the common keys with their
defaults.

```yaml
# config/packages/fhir.yaml
fhir:
    # Where generated FHIR classes are written (used by fhir:generate)
    output_directory: '%kernel.project_dir%/output'

    # Cache directory for FHIR package downloads and metadata
    cache_directory: '%kernel.cache_dir%/fhir'

    # Default FHIR version: R4, R4B, or R5
    default_version: 'R4'

    validation:
        enabled: true              # enable validation during code generation
        strict_mode: false         # fail on warnings, not just errors
        message_overrides: []      # map of simple constraint class name => message template
        terminology_cache_pool: null   # PSR-6 pool for terminology results; null disables
        terminology_cache_ttl: 3600    # seconds; 0 = no expiry

    serialization:
        metadata_cache_pool: 'cache.app'   # PSR-6 pool for property metadata; null disables
        enable_cache_warmer: false         # pre-populate metadata cache during cache:warmup

    path:
        cache_size: 100            # max compiled FHIRPath expressions to cache (10–10000)

    # FHIR core packages to make available, keyed by package name
    packages:
        hl7.fhir.r4b.core:
            version: '4.3.0'
            # url: 'https://...'    # optional custom package URL
            # auto_update: false    # auto-update to the latest version

    # Implementation Guide generation (see fhir:generate-ig)
    ig:
        packages: []
        offline: false
        output_directory: null     # defaults to output_directory/IG
        namespace: null            # must match the PSR-4 mapping for the IG output directory
```

{% hint style="info" %}
Configuration is validated. An unknown key — or a `default_version` outside `R4`/`R4B`/`R5` —
fails the build with an `Invalid configuration for path "fhir.…"` error.
{% endhint %}

See the [Configuration Reference](../reference/configuration.md) for every key, type, default, and
the container parameter each value binds to.

### Metadata caching

FHIR property metadata is resolved from PHP attributes via reflection. A PSR-6 cache pool stores it
so it does not need re-resolving on every request.

- `serialization.metadata_cache_pool` — the cache pool service ID (default `cache.app`). The pool
  is aliased as `fhir.metadata_cache` and injected into `PropertyMetadataProvider`. Set to `~`
  (null) to disable persistent caching.
- `serialization.enable_cache_warmer` — when `true`, registers a `kernel.cache_warmer` that
  pre-populates the pool during `bin/console cache:warmup`. Defaults to `false`; only takes effect
  when `metadata_cache_pool` is set.

```yaml
fhir:
    serialization:
        metadata_cache_pool: cache.fhir_metadata
        enable_cache_warmer: true
```

### Terminology result caching

When a real `FHIRTerminologyClientInterface` (e.g. `HttpFHIRTerminologyClient`) is wired, the
bundle can wrap it with `CachingFHIRTerminologyClient` to avoid repeated lookups for the same
code/value-set pair.

- `validation.terminology_cache_pool` — the PSR-6 pool service ID. When set, the pool is aliased as
  `fhir.terminology_cache` and the caching client is registered as a decorator over
  `FHIRTerminologyClientInterface`. Defaults to `~` (null, disabled).
- `validation.terminology_cache_ttl` — TTL in seconds (default `3600`); `0` means no expiry.

```yaml
fhir:
    validation:
        terminology_cache_pool: cache.app
        terminology_cache_ttl: 3600
```

{% hint style="info" %}
The default terminology client is `NullFHIRTerminologyClient`. Caching only adds value once a real
server client is wired (see [Services & Dependency Injection](services.md)).
{% endhint %}

### Environment-specific overrides

Standard Symfony per-environment config applies. For example, enabling the metadata cache warmer
only in production:

```yaml
# config/packages/prod/fhir.yaml
fhir:
    serialization:
        enable_cache_warmer: true
```

The Flex recipe ships dev/prod/test overrides out of the box — see [Flex Recipe](flex-recipe.md).

## Troubleshooting

| Symptom | Check |
| --- | --- |
| `Bundle "FHIRBundle" not found` | Registered in `config/bundles.php` with FQCN `Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle`. |
| `Service "fhir.serialization_service" not found` | `php bin/console cache:clear`, then `php bin/console debug:container fhir`. |
| `Invalid configuration for path "fhir.…"` | An unsupported key — compare against the [Configuration Reference](../reference/configuration.md). |

```bash
php bin/console debug:container fhir   # inspect registered services
php bin/console debug:config fhir      # dump the effective configuration
```
