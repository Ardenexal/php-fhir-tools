---
description: FHIRPath operators, precedence, and language features.
icon: code
---

# Expressions & Operators

FHIRPath expressions navigate and transform FHIR data. They combine path navigation, function
calls, and operators. Every expression evaluates against an input collection and produces an
output collection.

## Operators

The evaluator implements arithmetic, comparison, equivalence, logical, string, collection,
membership, and type operators.

| Category | Operators | Notes |
|----------|-----------|-------|
| **Arithmetic** | `+`, `-`, `*`, `/`, `div`, `mod` | `div` is integer division; `mod` is remainder |
| **Comparison** | `=`, `!=`, `<`, `>`, `<=`, `>=` | |
| **Equivalence** | `~`, `!~` | Equivalence / non-equivalence (tokens `EQUIVALENT` / `NOT_EQUIVALENT`) |
| **Logical** | `and`, `or`, `xor`, `implies` | Three-valued logic |
| **String** | `&` | String concatenation |
| **Collection** | `\|` | Union |
| **Membership** | `in`, `contains` | |
| **Type** | `is`, `as` | Type testing and casting |

{% hint style="info" %}
Equivalence (`~` / `!~`) is implemented: it is recognised by the lexer, parsed as a binary
operator, and dispatched in the evaluator to the comparison service. (Earlier drafts described
it as unimplemented — that is no longer accurate.)
{% endhint %}

### Examples

```php
// Comparison and logical
$service->evaluate('age > 18 and status = "active"', $patient);
$service->evaluate('value > 100 or value < 0', $observation);

// Arithmetic (precedence: * / div mod bind tighter than + -)
$service->evaluate('value * 2', $observation);
$service->evaluate('(value + 10) / 2', $observation);

// Membership and union
$service->evaluate('"phone" in telecom.system', $patient);
$service->evaluate('name | telecom', $patient);

// Type operators
$service->evaluate('value is Quantity', $observation);
$service->evaluate('value as Quantity', $observation);
```

## Language features

The parser and evaluator support the core FHIRPath 2.0 syntax:

| Feature | Example |
|---------|---------|
| Path navigation | `Patient.name.given` |
| Indexing | `name[0]` |
| Function calls | `name.where(use = "official")` |
| Literals | strings, numbers, booleans, date/time, quantities |
| Collection literals | `{}` (empty), `{1, 2, 3}` |
| External constants | `%context`, `%resource`, … |
| Reserved identifiers | `$this`, `$index`, `$total` |

### Type system

Types are resolved through `FHIRTypeResolver` and exposed as `TypeInfo`. The `is` operator tests
whether an item conforms to a named type; `as` filters the collection to items of that type.
Temporal values use dedicated types (`FHIRPathDate`, `FHIRPathDateTime`, `FHIRPathTime`) and
decimals use `FHIRPathDecimal` to preserve precision.

See the [Function Reference](functions/README.md) for the full library of built-in functions,
including type-conversion helpers such as `toInteger()`, `toQuantity()`, and `ofType()`.
