---
description: Validate codes against ValueSet bindings (required, extensible, preferred).
icon: list-check
---

# Terminology & Binding Validation

A coded element in FHIR binds to a *ValueSet* — a named set of permitted codes. The binding's
*strength* says how strictly that set is enforced: `required` (the code must be a member),
`extensible` or `preferred` (membership is expected, other codes are allowed), or `example`
(illustrative only). This page validates coded values against those bindings using
`FHIRValueSetBindingValidator`, driven by the `#[FHIRValueSetBinding]` attribute.

{% hint style="info" %}
Extensible and preferred bindings may call an external terminology server. Configure the
terminology client (and result caching) on the [Configuration](configuration.md) page.
{% endhint %}

## Binding strengths

The validator branches on the constraint's `strength`:

| Strength | Behaviour |
|---|---|
| `required` | Checked against the generated backed enum for the value set. If no enum class exists, falls back to the terminology client; with no client, emits a WARNING (`Required binding ... could not be validated: no enum class generated`). |
| `extensible` | Checked via the terminology client. Without a real client → `fhir:unchecked-binding` INFO. |
| `preferred` | Same as extensible. |
| `example` | Never validated and never surfaced as unchecked (documentation only). |

### Required bindings

For `required` bindings the validator resolves a backed enum class from the value set URL (across
the configured enum namespace roots, e.g. `Ardenexal\FHIRTools\Component\Models\R4\Enum`). A value
that is not a valid enum case raises an ERROR:

```
The value {{ value }} is not a valid case of value set {{ url }}.
```

Array-valued (repeating) properties are validated element by element. `\Stringable` primitive
wrappers are coerced to string before the `tryFrom()` check.

### Extensible / preferred bindings

These are checked through the terminology client, as is a `required` binding with no generated
enum. A `Coding` is sent with its system through `validateCoding()`. A code with no system, such as
a `code` element, goes through `validateCode()`, which `HttpFHIRTerminologyClient` sends with
`inferSystem=true`. A `CodeableConcept` meets the binding when any one of its codings is in the value
set; a concept with no member is one finding naming its codes. Behaviour depends on the constraint
flags:

* Default failures use WARNING (`fhir:warning`); `strict = true` escalates to ERROR.
* When `maxValueSetUrl` is set, a value outside that max value set always produces an ERROR,
  regardless of `strict`.

When **no real client is configured** — the client is `null` or a `NullFHIRTerminologyClient` —
the check is skipped and a single `fhir:unchecked-binding` INFO violation is emitted instead:

```
Terminology validation for value set {{ url }} was skipped: no terminology client is configured.
```

A check the server gave no answer to (a non-2xx response, a timeout, an unreadable body) is reported
the same way, never as an invalid code, with this message instead:

```
Terminology validation for value set {{ url }} was skipped: the terminology server gave no answer.
```

This needs a client that implements `FHIRTerminologyMembershipClientInterface`, whose `checkCode()`
and `checkCoding()` return `null` for no answer. `HttpFHIRTerminologyClient` and
`CachingFHIRTerminologyClient` do; a client that implements only `FHIRTerminologyClientInterface`
answers `false`, which is reported as an invalid code.

This INFO never affects `isValid()`. Query it via
`FHIRValidationReport::hasUncheckedBindings()` / `uncheckedBindings()`:

```php
$report = $service->validate($patient);

if ($report->hasUncheckedBindings()) {
    foreach ($report->uncheckedBindings() as $unchecked) {
        echo $unchecked->message . "\n";
    }
}
```

## Profile bindings

A profile's own `required` and `extensible` bindings are emitted as `#[FHIRProfileBinding]` on the
profile class, with the element `path` they apply to and the profile URL as their group. A binding
the base type already declares with the same strength and value set is left out, because the base
model checks it. `FHIRProfileBindingValidator` checks each occurrence the way
`#[FHIRValueSetBinding]` would, with the same enums and terminology client, and reports at the
element's path. Two things differ:

* Only an enum generated from that exact value set, in the element's own release, is used. A profile
  names a value set by URL, so an enum whose class name merely matches the URL is not trusted, and an
  R5 element is not checked against the R4 enum of a value set whose codes changed between releases.
* A required binding that neither an enum nor a terminology client can check is reported as
  `fhir:unchecked-binding` INFO, not as a missing enum.

Bindings on an element inside a slice are not enforced yet. Generation reports each one as a
warning, and runtime compilation as a `CompilerDiagnostic`.

## Message overrides

Invalid-code and missing-enum messages use the `FHIRValueSetBinding` registry key; the
unchecked-binding message uses the distinct `FHIRValueSetBindingUnchecked` key. See
[Configuration](configuration.md) for the terminology client, caching, and registry setup.
