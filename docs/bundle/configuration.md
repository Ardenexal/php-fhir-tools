---
description: Install and configure the FHIR Symfony bundle.
icon: gear
---

# Installation & Configuration

The bundle registers all FHIR Tools services automatically in a Symfony application.

## Install

```bash
composer require ardenexal/fhir-bundle
```

<!-- TODO: migrate manual registration note from src/Bundle/FHIRBundle/README.md -->

## Configuration

All keys under the `fhir` root are optional.

```yaml
# config/packages/fhir.yaml
fhir:
    # TODO: migrate the configuration tree (output dir, cache dir, default version,
    # validation, serialization, path, packages, ig)
```

### Metadata caching

<!-- TODO: migrate PSR-6 metadata caching + cache warmers -->

### Terminology result caching

<!-- TODO: migrate terminology result caching -->

### Environment-specific overrides

<!-- TODO: migrate dev/prod/test overrides -->

See the [Configuration Reference](../reference/configuration.md) for the full key list.

<!-- MIGRATION SOURCE: src/Bundle/FHIRBundle/README.md + docs/component-guides/fhir-bundle.md
     (Installation, Configuration). This page is canonical. -->
