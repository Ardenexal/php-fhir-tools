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
Profile validation uses the pre-generated models (see
[Code Generation](../code-generation/overview.md)), or StructureDefinitions you load at runtime
(see [Runtime Profiles](runtime-profiles.md)).
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

A profile URL that no generated class declares is checked against its StructureDefinition when
the service has a runtime profile resolver. Without one, the URL is ignored and produces no
violation. With one, a profile that cannot be resolved is reported as a `fhir:profile-not-checked`
warning. See [Runtime Profiles](runtime-profiles.md).

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

`SliceDiscriminatorMatcher` evaluates `value`, `pattern` and `exists` discriminators. A slice that
needs a `type` or `profile` discriminator, `$this`, or a path through `resolve()` matches no item.
The generator and the runtime compiler enforce closed or openAtEnd slicing that depends on such a
slice as open, and report a warning, so a conforming resource is not rejected.

## Fixed and pattern values

`FHIRFixedValueValidator` enforces `#[FHIRFixedValue]`: the instance value must exactly equal the
declared `fixed[x]` value. FHIR primitive wrapper objects (which are `\Stringable`) are coerced
to string before comparison, so correctly-valued wrappers are not flagged, and a fixed decimal is
compared by value, so `4.50` equals a fixed `4.5`. A mismatch raises an ERROR with the default
message:

```
Value is '{{ value }}' but is fixed to '{{ fixed }}'
```

A complex fixed value, such as `fixedCodeableConcept` or `fixedQuantity`, is compared as exact
structure. Every element it carries must match, repeating elements must hold the same number of
items in the same order, and an element the instance carries that the fixed value does not is an
error too. Each difference is reported separately at the element it concerns, for example
`code.coding[0].code` or `referenceRange.high.unit`:

```
The element {{ element }} is present in the instance but not allowed in the applicable fixed value specified in profile
Missing element '{{ element }}' - required by the applicable fixed value specified in profile
Expected {{ expected }} but found {{ actual }} {{ element }} element(s) in the applicable fixed value specified in profile
```

An extension's definition fixes `Extension.url` to its canonical URL, so an extension written with a
version suffix (`http://hl7.org/fhir/StructureDefinition/patient-congregation|4.0.0`) is reported
against that fixed URL when the unversioned URL names a known extension.

`FHIRPatternValueValidator` enforces `#[FHIRPatternValue]`: the instance must contain at least
the declared `pattern[x]` value (partial-match semantics, rather than exact equality). A mismatch
raises an ERROR.

Both validators accept a message override via `FHIRValidationMessageRegistry` (see
[Configuration](configuration.md)).
