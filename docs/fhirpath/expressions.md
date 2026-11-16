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
`~` tests equivalence and `!~` its negation. Equivalence differs from `=` by ignoring collection
order and, for strings, case and whitespace.
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

### Environment variables

| Variable | Resolves to |
|----------|-------------|
| `%context` | The context's root resource. When the context has none, the first `evaluate()` call sets it to that call's focus, and the context keeps it |
| `%resource` | The resource bound with `EvaluationContext::withResourceNode()`, or the focus when none is bound |
| `%rootResource` | The containing resource when the context carries one (constructor argument `containerResource`), otherwise the same node as `%resource` |
| `%ucum`, `%sct`, `%loinc` | `http://unitsofmeasure.org`, `http://snomed.info/sct`, `http://loinc.org` |
| ``%`vs-[name]` ``, ``%`ext-[name]` `` | `http://hl7.org/fhir/ValueSet/[name]`, `http://hl7.org/fhir/StructureDefinition/[name]`. Quote the name with backticks, because `-` is otherwise read as subtraction |

An unknown name throws an `EvaluationException`. A constant you supply on the context with
`withExternalConstant()` takes precedence over these built-ins.

Bind `%resource` separately when the focus is a node inside a resource rather than the resource
itself. SDC does this to evaluate an expression on a QuestionnaireResponse item while `%resource`
stays the QuestionnaireResponse:

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

$context = (new EvaluationContext())
    ->withResourceNode($questionnaireResponse)
    ->withExternalConstant('patient', $patient);

// Focus is the item; %resource is the QuestionnaireResponse; %patient is the launch-context Patient.
// The first call fixes the context's root resource (%context) to $item, so reuse a context only for
// the same focus.
$result = $service->evaluate('answer.value', $item, $context);
$status = $service->evaluate('%resource.status', $item, $context);
```

### Type system

The `is` operator tests whether an item conforms to a named type, and `as` filters the collection
to items of that type. Temporal values use dedicated types (`FHIRPathDate`, `FHIRPathDateTime`,
`FHIRPathTime`), and decimals use `FHIRPathDecimal` to preserve precision.

See the [Function Reference](functions/README.md) for the full library of built-in functions,
including type-conversion helpers such as `toInteger()`, `toQuantity()`, and `ofType()`.
