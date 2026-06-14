---
description: Generate PHP model classes from FHIR Structure Definitions and ValueSets.
icon: gears
---

# Overview

The Code Generation component loads FHIR packages from the registry and generates strongly-typed PHP
classes — Resources, DataTypes, Primitives, and Enums — from their Structure Definitions and
ValueSets. It is the engine behind the [`fhir:generate`](base-models.md) and
[`fhir:generate-ig`](implementation-guides.md) console commands.

Namespace: `Ardenexal\FHIRTools\Component\CodeGeneration\`.

There are two distinct workflows:

<table data-view="cards">
  <thead>
    <tr><th>Workflow</th><th data-card-target data-type="content-ref">Page</th></tr>
  </thead>
  <tbody>
    <tr><td>Generate the base FHIR models (R4 / R4B / R5)</td><td><a href="base-models.md">Base FHIR Models</a></td></tr>
    <tr><td>Generate typed extension &#x26; profile classes for an Implementation Guide</td><td><a href="implementation-guides.md">Implementation Guides</a></td></tr>
  </tbody>
</table>

## How it works

Both commands share the same pipeline, implemented with
[Nette PhpGenerator](https://github.com/nette/php-generator):

1. **Package loading** — `PackageLoader` downloads and caches FHIR packages (`.tgz`) from the FHIR
   registry (`packages.fhir.org`), verifying integrity and resolving versions. Cached packages can
   be reused with `--offline`.
2. **Definition parsing** — `loadPackageStructureDefinitions()` reads `StructureDefinition` and
   `ValueSet` JSON resources out of each package into a per-version `BuilderContext`.
3. **Class generation** — each `StructureDefinition` is routed by its `kind` to a generator that
   emits a PHP class or enum:
   - `kind: resource` → Resources (`PatientResource`, `ObservationResource`, …)
   - `kind: complex-type` → DataTypes (`HumanName`, `Address`, …)
   - `kind: primitive-type` → Primitives (`StringPrimitive`, `BooleanPrimitive`, …)
   - referenced `ValueSet` bindings → Enums (`AdministrativeGender`, …) plus a `…Type` code
     wrapper class in `DataType/`

`fhir:generate` runs in three global phases — **load** all requested packages, **clear** only the
output directories for the affected FHIR versions, then **generate** — so generating one version
never clobbers another. It skips `logical` models and `derivation: constraint` definitions (profiles
and named extensions); those are the domain of [`fhir:generate-ig`](implementation-guides.md).

### Core classes

| Class | Location under `…\CodeGeneration\` | Responsibility |
|-------|------------------------------------|----------------|
| `PackageLoader` | `Package\PackageLoader` | Download, cache, extract, and parse FHIR packages |
| `BuilderContext` | `Context\BuilderContext` | Hold definitions, generated types, enums, and namespace registrations during a run |
| `FHIRModelGenerator` | `Generator\FHIRModelGenerator` | Generate a PHP class from a base `StructureDefinition` |
| `FHIRValueSetGenerator` | `Generator\FHIRValueSetGenerator` | Generate a PHP enum from a `ValueSet` |
| `FHIRExtensionGenerator` | `Generator\FHIRExtensionGenerator` | Generate typed extension classes (`type: Extension`) |
| `FHIRProfileGenerator` | `Generator\FHIRProfileGenerator` | Generate profile subclasses (`derivation: constraint`) |
| `ErrorCollector` | `Generator\ErrorCollector` | Collect non-fatal errors/warnings for end-of-run reporting |

See [Base FHIR Models](base-models.md) for the generator APIs in detail and
[Implementation Guides](implementation-guides.md) for the extension/profile generators.

{% hint style="warning" %}
Generated model files under `src/Component/Models/src/` are **never hand-edited** — always
regenerate. See [Generated Output Structure](output-structure.md).
{% endhint %}
