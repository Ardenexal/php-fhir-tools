---
description: The Symfony Flex recipe that auto-configures the bundle.
icon: wand-magic-sparkles
---

# Flex Recipe

A Symfony Flex recipe automates bundle setup: registration, config files, environment variables, and
`.gitignore` entries.

## What the recipe does

When you run `composer require ardenexal/fhir-bundle` in a Flex-enabled application, the recipe
manifest performs the following:

- **Registers the bundle** in `config/bundles.php` for all environments — the FQCN is
  `Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle` (there is **no** `\src` segment, even though the
  file lives under `src/`).
- **Copies configuration** into `config/packages/fhir.yaml`, plus per-environment overrides for
  `dev`, `prod`, and `test`.
- **Adds environment variables** to `.env` (see below).
- **Adds `.gitignore` entries** for generated output and cache: `/output/` and `/var/cache/fhir/`.

The main config file wires the bundle settings to environment variables and pre-configures the core
FHIR packages:

```yaml
# config/packages/fhir.yaml
fhir:
    output_directory: '%env(FHIR_OUTPUT_DIRECTORY)%'
    cache_directory: '%env(FHIR_CACHE_DIRECTORY)%'
    default_version: '%env(FHIR_DEFAULT_VERSION)%'
    validation:
        enabled: '%env(bool:FHIR_VALIDATION_ENABLED)%'
        strict_mode: '%env(bool:FHIR_VALIDATION_STRICT_MODE)%'
    packages:
        'hl7.fhir.r4.core':
            version: '4.0.1'
            auto_update: false
        'hl7.fhir.r4b.core':
            version: '4.3.0'
            auto_update: false
        'hl7.fhir.r5.core':
            version: '5.0.0'
            auto_update: false
        'hl7.terminology':
            version: '5.5.0'
            auto_update: false
```

The recipe also ships `when@prod` (strict validation on) and `when@test` (separate output/cache
directories) overrides in the same file.

## Environment variables

The recipe seeds these variables in `.env`. They feed the `%env(...)%` placeholders in
`config/packages/fhir.yaml`.

| Variable | Default | Purpose |
| --- | --- | --- |
| `FHIR_OUTPUT_DIRECTORY` | `%kernel.project_dir%/output` | Where generated FHIR classes are written |
| `FHIR_CACHE_DIRECTORY` | `%kernel.cache_dir%/fhir` | Cache directory for FHIR packages and metadata |
| `FHIR_DEFAULT_VERSION` | `R4B` | Default FHIR version: `R4`, `R4B`, or `R5` |
| `FHIR_VALIDATION_ENABLED` | `true` | Enable or disable FHIR validation |
| `FHIR_VALIDATION_STRICT_MODE` | `false` | Fail on warnings, not just errors |

For the full `fhir` configuration tree (caching, IG generation, message overrides, and more), see
[Configuration](configuration.md).

## Installation methods

{% tabs %}
{% tab title="Automatic (recommended)" %}
With Symfony Flex enabled, a single command installs and configures the bundle:

```bash
composer require ardenexal/fhir-bundle
```

Flex then registers the bundle, copies the config files, adds the environment variables to `.env`,
and updates `.gitignore` automatically.

Verify the recipe was applied:

```bash
php bin/console debug:container fhir   # bundle services registered
php bin/console debug:config fhir      # effective configuration
```
{% endtab %}

{% tab title="Manual" %}
If you skip recipes (or Flex is not installed), perform the steps the recipe would have done.

Install without running the recipe:

```bash
composer require ardenexal/fhir-bundle --no-recipes
```

Register the bundle in `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

Add the environment variables to `.env`:

```bash
FHIR_OUTPUT_DIRECTORY=%kernel.project_dir%/output
FHIR_CACHE_DIRECTORY=%kernel.cache_dir%/fhir
FHIR_DEFAULT_VERSION=R4B
FHIR_VALIDATION_ENABLED=true
FHIR_VALIDATION_STRICT_MODE=false
```

Create `config/packages/fhir.yaml` (see the example above) and add the generated/cache paths to
`.gitignore`:

```
/output/
/var/cache/fhir/
```
{% endtab %}
{% endtabs %}

{% hint style="info" %}
Recipe maintenance and publishing (official vs. contrib vs. private) are documented for contributors,
not end users — see [Contributing](../contributing/setup.md).
{% endhint %}
