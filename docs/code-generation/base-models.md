---
description: Generate the base FHIR model classes with fhir:generate.
icon: cube
---

# Generating Base FHIR Models

Generate PHP classes for a base FHIR package (e.g. `hl7.fhir.r4.core`).

## Command

```bash
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv
```

<!-- TODO: migrate "Base FHIR models (fhir:generate)" usage + Composer Scripts from
     src/Component/CodeGeneration/README.md -->

## Composer scripts

```bash
composer run generate-models-all   # regenerate R4, R4B, R5
```

## Core classes

<!-- TODO: migrate FHIRModelGenerator, FHIRValueSetGenerator, PackageLoader, BuilderContext -->

<!-- MIGRATION SOURCE: src/Component/CodeGeneration/README.md (Base FHIR Model Generation) -->
