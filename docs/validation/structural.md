---
description: Cardinality, slicing, and fixed/pattern value validation against profiles.
icon: ruler-combined
---

# Structural & Profile Validation

Validates cardinality and required/optional constraints, slice constraints, and fixed/pattern values
against a resource's declared profiles.

{% hint style="info" %}
Profile validation requires the relevant pre-generated models. See
[Code Generation](../code-generation/overview.md).
{% endhint %}

Validators involved:

* `FHIRProfileConstraintValidator` — cardinality and profile value rules
* `FHIRSliceConstraintValidator` — open / closed / openAtEnd slices
* `FHIRFixedValueValidator` — `fixed[x]`
* `FHIRPatternValueValidator` — `pattern[x]`

<!-- TODO: migrate structural/profile/slice/fixed/pattern details from
     src/Component/Validation/README.md -->

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (structural validation, slices, fixed/pattern) -->
