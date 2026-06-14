---
description: FHIR-specific FHIRPath functions.
icon: notes-medical
---

# FHIR-Specific

These functions implement the FHIR R4 FHIRPath extension supplement. They are
registered in `FunctionRegistry` and implemented under
`Ardenexal\FHIRTools\Component\FHIRPath\Function\`. Eight functions are available:

| Function | Description | Example |
|----------|-------------|---------|
| `extension(url)` | Shortcut for `.extension.where(url = url)`. For each input item, returns the extensions whose `url` matches exactly (case-sensitive). | `Patient.extension('http://example.org/race')` |
| `hasValue()` | True when an item carries a primitive value (not extensions only). For FHIR primitive wrappers, checks that `value` is non-null; empty input returns empty. | `Patient.birthDate.hasValue()` |
| `getValue()` | Unwraps FHIR primitive wrappers to their underlying system value (e.g. `FHIRString` → PHP `string`). Scalars pass through; complex/null items are omitted. | `Patient.active.getValue()` |
| `resolve()` | Resolves each `Reference` (or reference string) to the resource it points at. Contained (`#id`) references resolve locally; unresolvable items are silently dropped. | `Observation.subject.resolve()` |
| `memberOf(valueSet)` | True when a code, `Coding`, or `CodeableConcept` is a member of the given ValueSet, via the terminology server's `$validate-code` operation. | `Observation.code.memberOf('http://hl7.org/fhir/ValueSet/observation-codes')` |
| `conformsTo(structure)` | True when the single input item conforms to the StructureDefinition at the given canonical URL. Delegates to a user-supplied validator callable. | `Patient.conformsTo('http://hl7.org/fhir/StructureDefinition/Patient')` |
| `comparable(other)` | True when the input value and the argument are of the same orderable type (numeric, string, boolean, or commensurable Quantities). | `(1 'cm').comparable(2 'm')` |
| `htmlChecks()` | True when a single string (typically `Narrative.div`) is conformant FHIR Narrative XHTML. | `Patient.text.div.htmlChecks()` |

## Runtime configuration

{% hint style="warning" %}
Three functions need runtime configuration on the `FHIRPathEvaluator` before they
return useful results, and throw an `EvaluationException` when it is missing:

* **`resolve()`** — for non-contained references, needs a FHIR server URL and a
  PSR-18 HTTP client. Fragment-only (`#id`) references resolve against
  `contained` without any HTTP setup.
* **`memberOf()`** — requires a terminology URL (falls back to the FHIR server URL)
  **and** a PSR-18 HTTP client / PSR-17 request factory; otherwise it throws.
* **`conformsTo()`** — requires a validator callback; profile validation has no
  universal HTTP pattern, so it delegates to your engine and throws when no
  callback is set.

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;

$evaluator->setFhirServerUrl('https://r4.smarthealthit.org');
$evaluator->setTerminologyUrl('https://tx.fhir.org/r4');
$evaluator->setHttpClient($psr18Client, $psr17RequestFactory);
$evaluator->setConformsToValidator(
    fn (mixed $resource, string $url): bool => $myProfileEngine->validate($resource, $url),
);
```
{% endhint %}

{% hint style="info" %}
`htmlChecks()`, `extension()`, `hasValue()`, `getValue()`, and `comparable()` need no
external configuration. `htmlChecks()` parses with PHP's `DOMDocument` and rejects
forbidden elements (`script`, `form`, `iframe`, `object`, …), event-handler
attributes (`on*`), and `<img>` without an `alt`.
{% endhint %}

See [Implementation Status & Known Issues](../status.md) for conformance notes, and the
[Function Reference](README.md) for the other function categories.
