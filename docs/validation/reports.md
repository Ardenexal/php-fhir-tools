---
description: The structure of validation reports, violations, and violation codes.
icon: file-lines
---

# Validation Reports & Violation Codes

A validation run returns a structured, immutable report:

* `FHIRValidationReport` — the overall result, a collection of violations grouped by severity
* `FHIRValidationViolation` — an individual finding
* `FHIRViolationCode` — the canonical violation-code constants

All three live in `Ardenexal\FHIRTools\Component\Validation`.

## FHIRValidationReport

The report wraps a `list<FHIRValidationViolation>` (its single constructor argument,
exposed as the public readonly `$violations`) and offers severity-filtered accessors.

| Method | Returns | Notes |
|--------|---------|-------|
| `isValid()` | `bool` | True when there are **no error-severity** violations. Warnings and info do not affect validity. |
| `hasErrors()` | `bool` | True when at least one error-severity violation exists. |
| `hasWarnings()` | `bool` | True when at least one warning-severity violation exists. |
| `errors()` | `list<FHIRValidationViolation>` | Error-severity violations only. |
| `warnings()` | `list<FHIRValidationViolation>` | Warning-severity violations only. |
| `info()` | `list<FHIRValidationViolation>` | Info-severity violations only. |
| `hasUncheckedBindings()` | `bool` | True when any extensible/preferred binding check was skipped. |
| `uncheckedBindings()` | `list<FHIRValidationViolation>` | Violations with code `FHIRViolationCode::UNCHECKED_BINDING`. |

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;

if (!$report->isValid()) {
    foreach ($report->errors() as $violation) {
        printf("%s: %s\n", $violation->path, $violation->message);
    }
}
```

{% hint style="info" %}
`hasUncheckedBindings()` reports a terminology *coverage gap* — extensible or preferred
binding checks skipped because no real terminology client was configured (a null client
or a `NullFHIRTerminologyClient`). It does **not** affect `isValid()`.
{% endhint %}

## FHIRValidationViolation

A single finding. All properties are public and readonly.

| Property | Type | Description |
|----------|------|-------------|
| `severity` | `string` | `'error'`, `'warning'`, or `'info'`. |
| `path` | `string` | FHIR property path, e.g. `'identifier[0].system'`. |
| `message` | `string` | Human-readable description, with template parameters already applied. |
| `constraintClass` | `string` | FQCN of the Symfony constraint that produced the violation. |
| `profileGroup` | `?string` | Profile canonical URL when the violation came from a profile group, else `null`. |
| `invariantKey` | `?string` | FHIRPath invariant key (e.g. `'obs-7'`), else `null`. |
| `parameters` | `array<string, mixed>` | Raw message-template parameters, e.g. `['{{ limit }}' => 1]`. Defaults to `[]`. |
| `code` | `?string` | Raw violation code (e.g. `'fhir:eval-error'`), else the underlying Symfony constraint code, which may be `null`. |

Severity is derived from the violation code: `fhir:error` → `error`, `fhir:warning` →
`warning`, `fhir:info` → `info`, while `fhir:eval-error` and `fhir:unchecked-binding`
both map to `info`. Any other code (such as built-in Symfony constraints like `Count` or
`NotBlank`) maps to `error`.

## FHIRViolationCode

`FHIRViolationCode` is a final class of string constants (not an enum). Its cases:

| Constant | Value | Meaning |
|----------|-------|---------|
| `FHIRViolationCode::ERROR` | `fhir:error` | Instance non-conformance — affects validity. |
| `FHIRViolationCode::WARNING` | `fhir:warning` | Conformance concern, not a hard failure. |
| `FHIRViolationCode::INFO` | `fhir:info` | Informational note. |
| `FHIRViolationCode::EVAL_ERROR` | `fhir:eval-error` | A FHIRPath invariant could not be evaluated (engine limitation, e.g. an unsupported function). Tooling incompleteness, not non-conformance — surfaced at info severity. |
| `FHIRViolationCode::UNCHECKED_BINDING` | `fhir:unchecked-binding` | An extensible/preferred binding check was skipped (no real terminology client). A coverage gap, not non-conformance — surfaced at info severity. |

{% hint style="info" %}
`fhir:eval-error` and `fhir:unchecked-binding` describe *tooling limitations*, not
problems with the resource. Consumers can inspect `FHIRValidationViolation::$code` to
distinguish them from genuine non-conformance.
{% endhint %}

To turn a report into a FHIR `OperationOutcome` resource, see
[The $validate Operation](operation-outcome.md), which documents
`FHIRValidationReportMapper::toOperationOutcome()` and the violation → issue mapping.
