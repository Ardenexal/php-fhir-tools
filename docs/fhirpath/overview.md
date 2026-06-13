---
description: Evaluate FHIRPath 2.0 expressions against FHIR data.
icon: magnifying-glass
---

# Overview & Quick Start

A FHIRPath 2.0 expression evaluator: path navigation, filtering, aggregation, ~100 built-in
functions, and a FHIR-aligned type system.

## Quick start

<!-- TODO: migrate Quick Start from src/Component/FHIRPath/README.md -->
```php
// $result = $fhirPath->evaluate('Patient.name.given', $patient);
```

## What's covered

* [Expressions & Operators](expressions.md)
* [Function Reference](functions/README.md) — grouped by category
* [Compilation, Caching & Performance](performance.md)
* [Implementation Status & Known Issues](status.md)

<!-- MIGRATION SOURCE: src/Component/FHIRPath/README.md (Features, Quick Start) -->
