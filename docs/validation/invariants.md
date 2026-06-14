---
description: Evaluate FHIRPath constraint expressions (invariants) during validation.
icon: function
---

# FHIRPath Invariant Validation

Validates FHIRPath invariant expressions defined on Structure Definitions, via
`FHIRPathInvariantValidator` (backed by the [FHIRPath](../fhirpath/overview.md) engine). Each
invariant is emitted as a `#[FHIRPathInvariant]` attribute carrying the invariant `key` (e.g.
`obs-7`), `expression`, `human` message, and `severity`.

## How it works

The validator runs the invariant's `expression` through `FHIRPathService::evaluate()` against the
validated value. The invariant **passes only when the result is the single boolean `true`**.
Anything else is treated as a failure.

On failure, the violation severity is derived from the constraint's `severity`:

* `severity === 'warning'` → `fhir:warning` (maps to `warning`)
* otherwise → `fhir:error` (maps to `error`)

The violation message defaults to the invariant's `human` text, overridable via the
`FHIRPathInvariant` key in `FHIRValidationMessageRegistry`. Null values are skipped (the
invariant does not fire on an absent element).

The resolved `invariantKey` is preserved on the report as
`FHIRValidationViolation::$invariantKey`, so consumers can correlate a violation back to its
source invariant.

## Engine limitations are not conformance failures

If the FHIRPath engine cannot evaluate an expression (for example, an unsupported function), the
validator catches the `FHIRPathException` and emits an INFO violation with the code
`fhir:eval-error` instead of an ERROR:

```
FHIRPath invariant `obs-7` could not be evaluated: <expression>
```

Per the FHIR conformance specification, a tooling limitation must not be asserted as instance
non-conformance, so these surface at `info` severity and never affect `isValid()`. Only FHIRPath
engine exceptions are downgraded this way — any other throwable (a genuine bug) propagates rather
than being masked.

{% hint style="info" %}
A valid resource will never receive a false-positive ERROR from an unsupported FHIRPath
expression. If your engine covers all invariant expressions in your StructureDefinitions, no
`fhir:eval-error` INFO violations appear.
{% endhint %}

See [Validation Reports & Violation Codes](reports.md) for how `fhir:eval-error` maps to severity.
