---
description: Evaluate FHIRPath 2.0 expressions against FHIR data.
icon: magnifying-glass
---

# Overview & Quick Start

FHIRPath is a query language for FHIR data, much like XPath for the resource tree. Use it to pull
fields, filter collections, and test conditions without walking PHP objects by hand. This component
is a FHIRPath 2.0 evaluator: path navigation, filtering, aggregation, a library of built-in
functions, and a FHIR-aligned type system with `is`/`as` support.

The public entry point is `FHIRPathService`
(`Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService`), which wraps lexing, parsing,
and evaluation behind three methods: `evaluate()`, `compile()`, and `validate()`.

## Quick start

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService();

// Evaluate against FHIR data — always returns a Collection
$result = $service->evaluate('Patient.name.given', $patient);

// Filtering and projection
$result = $service->evaluate('name.where(use = "official").given.first()', $patient);

// Boolean check
$hasPhone = $service->evaluate('telecom.where(system = "phone").exists()', $patient);
```

`evaluate()` returns a
`Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection` — FHIRPath is collection-centric,
so even scalar-looking results are wrapped in a collection.

The signature accepts optional arguments:

```php
public function evaluate(
    string $expression,
    mixed $resource,
    ?EvaluationContext $context = null,
    ?string $fhirVersion = null,   // 'R4' | 'R4B' | 'R5' hint for typed functions
    bool $strictMode = false       // enables runtime semantic validation
): Collection
```

### Validation

`validate()` parses without evaluating and returns a `bool`:

```php
$service->validate('name.given');     // true
$service->validate('name.given.???'); // false
```

### Error handling

All errors extend `Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException`
(syntax, token, parse, evaluation, and semantic exceptions all derive from it).

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;

try {
    $result = $service->evaluate('invalid..path', $patient);
} catch (FHIRPathException $e) {
    echo "FHIRPath error: {$e->getMessage()}";
}
```

## What's covered

* [Expressions & Operators](expressions.md)
* [Function Reference](functions/README.md) — grouped by category
* [Compilation, Caching & Performance](performance.md)
* [Implementation Status & Known Issues](status.md)
