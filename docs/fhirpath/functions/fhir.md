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
| `resolve()` | Resolves each `Reference` (or reference string) to the resource it points at. Contained (`#id`) references and references between the entries of a Bundle resolve locally; unresolvable items are silently dropped. | `Observation.subject.resolve()` |
| `memberOf(valueSet)` | True when a code, `Coding`, or `CodeableConcept` is a member of the given ValueSet, via the terminology server's `$validate-code` operation. | `Observation.code.memberOf('http://hl7.org/fhir/ValueSet/observation-codes')` |
| `conformsTo(structure)` | True when the single input item conforms to the StructureDefinition at the given canonical URL. Delegates to a user-supplied validator callable. | `Patient.conformsTo('http://hl7.org/fhir/StructureDefinition/Patient')` |
| `comparable(other)` | True when the input value and the argument are of the same orderable type (numeric, string, boolean, or commensurable Quantities). | `(1 'cm').comparable(2 'm')` |
| `htmlChecks()` | True when a single string (typically `Narrative.div`) is conformant FHIR Narrative XHTML. | `Patient.text.div.htmlChecks()` |

## Runtime configuration

{% hint style="warning" %}
Three functions need runtime configuration on the `FHIRPathEvaluator` before they
return useful results:

* **`resolve()`** — for references that cannot be answered locally, needs a FHIR
  server URL and a PSR-18 HTTP client, and omits the reference when they are missing.
  Fragment-only (`#id`) references resolve against `contained`, and references between
  the entries of a Bundle resolve against those entries, without any HTTP setup.
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

## `resolve()` inside a Bundle

When a reference is navigated out of a Bundle entry, or the root resource is a Bundle,
`resolve()` looks in the Bundle before making any HTTP request. It follows the FHIR rules for
[resolving references in Bundles](https://hl7.org/fhir/R4/bundle.html#references), on raw arrays
and on deserialised models alike:

| Reference | Resolves to |
|-----------|-------------|
| Absolute, including `urn:uuid:` | The entry with that `fullUrl` |
| Relative `Type/id` | The entry at the root of the referring entry's RESTful `fullUrl` plus `Type/id` |
| Version-specific (`.../_history/2`) | The entry with that `fullUrl` and `meta.versionId` |
| Fragment-only `#id` | The resource in the referring entry's own `contained` |
| `reference#id` | The resource in the `contained` of the entry the reference matches |

```php
$fhirPath->evaluate('Bundle.entry[1].resource.subject.resolve().name.family', $bundle);
```

* **Several matching entries** are ambiguous. The reference is omitted, with no HTTP request.
* **No matching entry** falls through to the HTTP strategies. A relative reference goes to the
  configured FHIR server, including one under a `urn:uuid:` `fullUrl`, where the Bundle itself
  gives it no meaning.
* **Knowing the referring entry.** It travels with each value through navigation, indexers and
  every function that returns its input or something derived from it, including `where()`,
  `select()`, `distinct()`, `union()`, `children()` and `descendants()`.
* **Equal values merged from different entries**, for example by `distinct()`, resolve only if every
  entry reaches the same resource. So `Bundle.entry.resource.subject.distinct().resolve()` works when
  they all mean one patient, and omits the value when the same `Patient/1` means different patients.
* **Values with no known entry** are literals, `$total`, the result of `aggregate()`, descendants of a
  root Bundle, and anything in an evaluation that starts inside an entry with the Bundle supplied as
  its root. A relative or fragment reference among them resolves only if every entry holding that value
  agrees on the target. If the root Bundle holds a nested Bundle anywhere, none of them is looked up,
  because they may belong to that Bundle, and no HTTP request is made.
* **Chained references.** A resolved resource keeps the entry it was found in, so
  `subject.resolve().managingOrganization.resolve()` follows the target's own base and `contained`.
  A resource fetched over HTTP is not treated as part of the Bundle: its `#id` references look in
  itself, and its relative references go to the configured server.
* **Invariants** are bound to the nearest enclosing resource, so an invariant's `resolve()` finds
  contained resources but not other entries of the Bundle.

See [Implementation Status & Known Issues](../status.md) for conformance notes, and the
[Function Reference](README.md) for the other function categories.
