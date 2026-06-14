---
description: Produce an OperationOutcome for the FHIR $validate operation.
icon: file-circle-check
---

# The $validate Operation

`FHIRValidationService::validateForOperation()` runs validation and returns a
standards-compliant `OperationOutcomeResource`, suitable for implementing the `$validate`
operation on a FHIR server. Pass the target FHIR version (`'R4'`, `'R4B'`, or `'R5'`) to
receive a version-typed resource.

```php
$outcome = $service->validateForOperation($patient, fhirVersion: 'R4');
// $outcome is an Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource

$outcome = $service->validateForOperation($patient, mode: 'create', fhirVersion: 'R5');
// $outcome is an Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource
```

The method signature is:

```php
public function validateForOperation(
    object $resource,
    string $mode = '',
    array $profileUrls = [],
    string $fhirVersion = 'R4',
): object
```

Accepted `mode` values are `''`, `create`, `update`, `profile`, and `delete`; any other value
throws `\InvalidArgumentException`. Internally the service builds a `FHIRValidationReport`,
then hands it to `FHIRValidationReportMapper::toOperationOutcome($report, $fhirVersion)`, which
produces the version-appropriate resource. An unsupported `$fhirVersion` throws
`\InvalidArgumentException`.

## Violation → OperationOutcome mapping

Each `FHIRValidationViolation` becomes one `OperationOutcomeIssue`. Severity maps directly;
`OperationOutcomeIssue.code` is derived from the violation's `code` and `constraintClass`.
The violation `path` is emitted as `OperationOutcomeIssue.expression` (omitted when empty),
and `message` becomes `diagnostics`.

| `FHIRValidationViolation::$severity` | `OperationOutcomeIssue::$severity` | `OperationOutcomeIssue::$code` |
|---|---|---|
| `error` | `error` | `invariant` (FHIRPath invariant), `value` (value-set binding), `invalid` (default) |
| `warning` | `warning` | same mapping as above |
| `info` (`fhir:eval-error`) | `information` | `not-supported` |
| `info` (`fhir:unchecked-binding`) | `information` | `not-supported` |
| `info` (`fhir:info`, general) | `information` | `informational` |

The `code` selection follows `FHIRValidationReportMapper::mapIssueType()`:

* `FHIRViolationCode::EVAL_ERROR`, `FHIRViolationCode::UNCHECKED_BINDING`, or any violation
  whose `constraintClass` is `FHIRValidationService::class` → `not-supported`.
* `FHIRViolationCode::INFO` → `informational`.
* Otherwise, by `constraintClass`: `FHIRPathInvariant::class` → `invariant`,
  `FHIRValueSetBinding::class` → `value`, default → `invalid`.

{% hint style="info" %}
Sub-codes such as `invariant` and `value` are not cases of the generated `IssueTypeType` enum
but are valid codes in the FHIR `issue-type` value set; the mapper passes them as raw strings.
{% endhint %}

When no violations are found, the outcome contains a single `information`-severity issue with
code `informational` and diagnostics `"No issues found — resource is valid."`

## $validate endpoint and HTTP semantics

The `$validate` operation endpoint per spec:

```text
POST [base]/[ResourceType]/$validate?profile=[profile-url]
```

The operation returns HTTP 200 OK whenever validation ran successfully, regardless of
findings. Structural errors (unparseable JSON/XML) may produce 4xx responses before this
library is reached.

{% hint style="warning" %}
`mode=delete` is not supported: referential integrity checks require a FHIR server context.
A call with `mode: 'delete'` returns an `information`-severity outcome explaining the
limitation rather than running resource validation.
{% endhint %}

See [Validation Reports & Violation Codes](reports.md) for the underlying report structure.
