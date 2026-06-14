---
description: Implementation status, conformance test coverage, and known issues.
icon: list-check
---

# Implementation Status & Known Issues

The FHIRPath component implements the core of the FHIRPath 2.0 language — a complete
lexer, parser, and evaluator — plus a large library of built-in functions and the FHIR R4
FHIRPath extensions. It is a partial implementation: most everyday navigation, filtering,
and conversion expressions work, while a small number of spec edge cases remain open.

## What's implemented

* **Operators** — arithmetic, comparison, equivalence (`~` / `!~`), logical, string
  concatenation, union, membership, and type (`is` / `as`). See
  [Expressions & Operators](expressions.md).
* **Functions** — a broad library spanning existence, filtering, subsetting, string, math,
  date/time, type, tree navigation, utility, combining, type conversion, FHIR-specific, and
  precision/boundary categories. The full, code-verified list is in the
  [Function Reference](functions/README.md).
* **Language features** — path navigation, indexing, function calls, literals (including
  date/time and quantity), collection literals `{}`, external constants (`%context` …),
  reserved identifiers (`$this`, `$index`, `$total`), and expression compilation/caching.

{% hint style="info" %}
Treat the [Function Reference](functions/README.md) as the authoritative inventory of registered
functions rather than a fixed count.
{% endhint %}

## Known issues

{% hint style="warning" %}
* **Semantic validation** — the parser does not reject every ambiguous expression that the spec
  requires parentheses for (e.g. constructs like `1 > 2 is Boolean`). A handful of conformance
  cases that exercise this fail.
* **FHIR deserialization** — many specification test cases cannot run because the test fixtures
  fail to deserialize from XML/JSON. This is a limitation in the Serialization component, not in
  FHIRPath itself, so those cases are skipped rather than failed.
{% endhint %}

{% hint style="info" %}
The `FunctionRegistry` is a singleton, which historically caused intermittent static-state
leakage between test suites. The registry now exposes a `reset()` method (used in test teardown)
to clear that state, mitigating the previously reported flakiness.
{% endhint %}

## Specification conformance

The component ships with the official FHIR FHIRPath specification test suite (loaded from the
`fhir/fhir-test-cases` package) and tracks progress against it. Conformance is **in progress**:
core expression and function tests pass, a small number fail on the semantic-validation edge
cases noted above, and a large block is skipped because of the deserialization limitation rather
than evaluator defects.

{% hint style="info" %}
Exact pass/fail/skip counts shift as work continues, so they are intentionally not quoted here.
Run the spec suite locally to get current numbers.
{% endhint %}
