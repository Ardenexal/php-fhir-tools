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

These are checked via `FHIRTerminologyClientInterface::validateCode()`. Behaviour depends on the
constraint flags:

* Default failures use WARNING (`fhir:warning`); `strict = true` escalates to ERROR.
* When `maxValueSetUrl` is set, a value outside that max value set always produces an ERROR,
  regardless of `strict`.

When **no real client is configured** — the client is `null` or a `NullFHIRTerminologyClient` —
the check is skipped and a single `fhir:unchecked-binding` INFO violation is emitted instead:

```
Terminology validation for value set {{ url }} was skipped: no terminology client is configured.
```

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

## Message overrides

Invalid-code and missing-enum messages use the `FHIRValueSetBinding` registry key; the
unchecked-binding message uses the distinct `FHIRValueSetBindingUnchecked` key. See
[Configuration](configuration.md) for the terminology client, caching, and registry setup.
