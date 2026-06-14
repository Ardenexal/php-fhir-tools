---
description: Cardinality, slicing, and fixed/pattern value validation against profiles.
icon: ruler-combined
---

# Structural & Profile Validation

Validates cardinality and required/optional constraints, slice constraints, and fixed/pattern
values against a resource's declared profiles. These constraints are emitted as PHP 8 attributes
on the generated model classes; Symfony Validator reads them and dispatches to the matching
validator.

{% hint style="info" %}
Profile validation requires the relevant pre-generated models. See
[Code Generation](../code-generation/overview.md). Runtime/dynamic StructureDefinitions are not
supported.
{% endhint %}

## Cardinality

Min/max cardinality is enforced by the generated Symfony constraints `#[NotBlank]` (required
elements) and `#[Count]` (repeating elements). These are standard Symfony constraints with no
FHIR-specific validator; their violations carry built-in Symfony codes and map to `error`
severity in the report.

## Profile constraints

`FHIRProfileConstraintValidator` enforces a class-level `#[FHIRProfileConstraint]`. It reads the
property value at the constraint's `path` via Symfony's `PropertyAccessor`, instantiates the
declared inner Symfony constraint with the provided options, and delegates validation to the
current execution context. Group propagation is handled by Symfony — the validator only runs
when the constraint's assigned groups are active, i.e. when the matching profile canonical URL
is passed to `validate()`:

```php
$report = $service->validate($patient, profileUrls: [
    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient',
]);
```

Passing an unknown profile URL is silently ignored — no violation, no error. Only profiles
pre-generated into PHP classes are supported.

## Slicing

`FHIRSliceConstraintValidator` validates slice membership on sliced array properties, driven by
the `#[FHIRSliceConstraint]` attribute (with `#[FHIRSlicingRules]` discriminators). Slice
matching is delegated to `SliceDiscriminatorMatcher`.

Symfony invokes the validator once per `#[FHIRSliceConstraint]` on the class. On the first
invocation for a `(property, active-group)` pair it runs a complete pass: it reads all slices
for that property, counts item matches by discriminator, checks each slice's min/max
cardinality, and — for `closed` slicing — rejects items matching no slice. Open / openAtEnd
slicing is supported. Subsequent invocations for the same `(property, group, object)` within one
`validate()` call are no-ops (deduplicated via a `WeakMap` keyed on the execution context), so
multiple slice attributes on one property never produce duplicate violations. Out-of-cardinality
or unmatched-closed items raise ERROR violations.

## Fixed and pattern values

`FHIRFixedValueValidator` enforces `#[FHIRFixedValue]`: the instance value must exactly equal the
declared `fixed[x]` scalar. FHIR primitive wrapper objects (which are `\Stringable`) are coerced
to string before comparison, so correctly-valued wrappers are not flagged. A mismatch raises an
ERROR with the default message:

```
The value {{ value }} does not match the required fixed value {{ fixed }}.
```

`FHIRPatternValueValidator` enforces `#[FHIRPatternValue]`: the instance must contain at least
the declared `pattern[x]` value (partial-match semantics, rather than exact equality). A mismatch
raises an ERROR.

Both validators accept a message override via `FHIRValidationMessageRegistry` (see
[Configuration](configuration.md)).
