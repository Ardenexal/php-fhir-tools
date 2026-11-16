---
description: Evaluate FHIRPath constraint expressions (invariants) during validation.
icon: function
---

# FHIRPath Invariant Validation

An *invariant* is a rule a FHIR resource must satisfy, written as a FHIRPath expression and
identified by a `key` such as `obs-7`. This validator evaluates those expressions via
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

## Profile invariants

A profile's own invariants are emitted as `#[FHIRProfileInvariant]` on the profile class, with the
element `path` they apply to and the profile URL as their group. A profile cannot add attributes to
the properties it inherits, so the class carries them. `FHIRProfileInvariantValidator` evaluates the
expression once per occurrence of the element and reports a failure at that occurrence, such as
`component[1]`. An empty `path` means the profiled resource or data type itself. `%resource` is the
resource around the element, as for base invariants.

Extension definitions work the same way: their invariants and bindings are attributes on the
generated extension class, with no group, and they apply wherever the extension is used. A plain
`Extension` whose definition was loaded at runtime is held to the same rules.

Invariants on an element inside a slice are not enforced yet, and neither are those on an extension
definition's sub-extensions. Generation reports each one as a warning, and runtime compilation as a
`CompilerDiagnostic`.
