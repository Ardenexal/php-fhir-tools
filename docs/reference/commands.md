---
description: Full reference for the fhir:* console commands.
icon: rectangle-terminal
---

# Console Command Reference

Complete argument and option reference for each command the bundle registers. For task-oriented
usage see [Bundle → Console Commands](../bundle/console-commands.md).

## `fhir:generate`

Generates canonical FHIR model classes from FHIR definition packages.

| Option | Shortcut | Type | Default | Description |
| --- | --- | --- | --- | --- |
| `--package` | — | array (repeatable) | `hl7.terminology.r4#7.0.0`, `hl7.fhir.r4.core#4.0.1`, `hl7.fhir.uv.extensions.r4#5.2.0` | Implementation Guide / core packages to include, as `name` or `name#version`. |
| `--offline` | — | flag | `false` | Work offline using only cached packages (no network downloads). |

Notes:

- The command takes **no positional arguments** — versions are not passed as arguments. The target
  FHIR version is derived from each package.
- A matching `hl7.terminology.*` package is prepended automatically when not supplied, based on the
  FHIR version segment of the requested packages (falling back to R4 terminology).
- Output is written to `src/Component/Models/src/{version}/`; only the directories for the affected
  versions are cleared before regeneration.
- Increase verbosity with `-v`/`-vvv` to see per-class output and stack traces on failure.

```bash
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv
php bin/console fhir:generate --package=hl7.fhir.r4b.core --offline
```

## `fhir:generate-ig`

Generates typed PHP classes for FHIR Implementation Guide extensions and profiles.

| Option | Shortcut | Type | Default | Description |
| --- | --- | --- | --- | --- |
| `--package` | — | array (repeatable) | `[]` (falls back to `fhir.ig.packages`) | IG packages to generate, in dependency order. Overrides `fhir.ig.packages` entirely when supplied. |
| `--offline` | — | flag | `false` (OR-ed with `fhir.ig.offline`) | Work offline using only cached packages. |

Notes:

- When `--package` is omitted, packages configured under
  [`fhir.ig.packages`](configuration.md#ig) are used. If neither is set, the command fails with an
  error.
- `--offline` is combined (logical OR) with the `fhir.ig.offline` config value.
- Output is written to `{ig.output_directory}/{version}/{slug}/{Extension|Profile}/`, defaulting to
  `Models/src/IG` when `ig.output_directory` is null. The namespace defaults to
  `Ardenexal\FHIRTools\Component\Models\IG` unless `ig.namespace` is set.
- List dependent IGs after their dependencies (e.g. `au.base` before `au.core`); out-of-order
  listing produces fallback FQCNs with a warning rather than a hard error.

```bash
php bin/console fhir:generate-ig
php bin/console fhir:generate-ig --package=hl7.fhir.us.core#6.1.0
php bin/console fhir:generate-ig --package=hl7.fhir.au.base#1.0.0 --package=hl7.fhir.au.core#1.0.0
```

## `fhir:path:evaluate`

Evaluates a FHIRPath expression against FHIR data.

| Argument | Required | Description |
| --- | --- | --- |
| `expression` | yes | FHIRPath expression to evaluate. |
| `data` | no | FHIR data: a JSON file path or a JSON string. Omit to evaluate against a null context. |

| Option | Shortcut | Type | Default | Description |
| --- | --- | --- | --- | --- |
| `--format` | `-f` | string | `text` | Output format: `json`, `text`, or `count`. |
| `--pretty` | `-p` | flag | `false` | Pretty-print JSON output. |

Notes:

- When `data` is an existing file path, it must resolve within the current working directory
  (path-traversal guard). Otherwise the value is treated as an inline JSON string.
- Expressions are capped at 10000 characters and a nesting depth of 50.
- Run with `-v` to print cache statistics (hits, misses, size) after evaluation.

```bash
php bin/console fhir:path:evaluate "5 + 3"
php bin/console fhir:path:evaluate "Patient.name.given" patient.json
php bin/console fhir:path:evaluate "name.given" patient.json --format=json --pretty
php bin/console fhir:path:evaluate "name.given" patient.json --format=count
```

## `fhir:path:validate`

Validates FHIRPath expression syntax.

| Argument | Required | Description |
| --- | --- | --- |
| `expression` | yes | FHIRPath expression to validate. |

Notes:

- Takes no options. Returns success (exit code 0) for valid syntax, failure (1) for invalid.
- Run with `-v` to print the compiled expression on success and error details on failure.

```bash
php bin/console fhir:path:validate "name.where(use = 'official').given.first()"
```
