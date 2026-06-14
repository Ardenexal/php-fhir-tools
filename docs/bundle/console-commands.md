---
description: Console commands provided by the FHIR bundle.
icon: terminal
---

# Run the bundle's console commands

The bundle adds these commands to a Symfony application:

| Command | Description |
| --- | --- |
| `fhir:generate` | Generate canonical FHIR model classes from core packages |
| `fhir:generate-ig` | Generate typed IG extension/profile classes |
| `fhir:path:evaluate` | Evaluate a FHIRPath expression against FHIR data |
| `fhir:path:validate` | Validate FHIRPath expression syntax |

This page is task-oriented. For the exhaustive argument and option reference, see the
[Console Command Reference](../reference/commands.md).

## Generate base FHIR models

Run once when setting up a project or upgrading the FHIR version. The command takes **no positional
version argument** — the target version is derived from each `--package`.

```bash
# Generate R4 models
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv

# Generate R4B models
php bin/console fhir:generate --package=hl7.fhir.r4b.core -vvv

# Offline mode (use cached packages only, no network)
php bin/console fhir:generate --package=hl7.fhir.r4.core --offline -vvv
```

A matching `hl7.terminology.*` package is prepended automatically when not supplied. Increase
verbosity with `-v`/`-vvv` to see per-class output and stack traces on failure.

{% hint style="warning" %}
`fhir:generate` writes into `src/Component/Models/src/` (generated output). Do not hand-edit those
files; regenerate them with this command.
{% endhint %}

## Generate Implementation Guide classes

`fhir:generate-ig` generates typed PHP classes for a FHIR Implementation Guide — named extension
subclasses and resource profile subclasses — into a namespace isolated from the base models.

The recommended approach is to configure packages under `fhir.ig` so the command needs no arguments
in CI or deploy scripts:

```yaml
# config/packages/fhir.yaml
fhir:
    ig:
        namespace: 'App\FHIR\IG'
        output_directory: '%kernel.project_dir%/src/FHIRIG'
        offline: false
        packages:
            - hl7.fhir.us.core
```

Then run with no arguments:

```bash
php bin/console fhir:generate-ig
```

The `--package` option overrides `fhir.ig.packages` entirely when supplied — useful for one-off
generation or CI pipelines. List dependent IGs after their dependencies:

```bash
# Single IG
php bin/console fhir:generate-ig --package=hl7.fhir.us.core

# Pin a specific version
php bin/console fhir:generate-ig --package=hl7.fhir.us.core#6.1.0

# Multi-level chain (AU Base before AU Core) — order matters
php bin/console fhir:generate-ig \
    --package=hl7.fhir.au.base#1.0.0 \
    --package=hl7.fhir.au.core#1.0.0
```

Add the generated directory to your `composer.json` autoloader and run `composer dump-autoload`:

```json
"autoload": {
    "psr-4": {
        "App\\FHIR\\IG\\": "src/FHIRIG/"
    }
}
```

## Evaluate FHIRPath expressions

```bash
# Evaluate against a null context
php bin/console fhir:path:evaluate "5 + 3"

# Evaluate against a JSON file
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Output formats
php bin/console fhir:path:evaluate "name.given" patient.json --format=json --pretty
php bin/console fhir:path:evaluate "name.given" patient.json --format=count

# Show cache statistics
php bin/console fhir:path:evaluate "name" patient.json -v
```

When `data` is an existing file path it must resolve within the current working directory
(path-traversal guard); otherwise the value is treated as an inline JSON string.

## Validate FHIRPath syntax

```bash
php bin/console fhir:path:validate "name.where(use = 'official').given.first()"
```

Returns exit code `0` for valid syntax, `1` for invalid. Run with `-v` to print the compiled
expression on success or error details on failure.

See [Code Generation](../code-generation/overview.md) and [FHIRPath](../fhirpath/overview.md) for the
underlying behavior, and the [Console Command Reference](../reference/commands.md) for full options.
