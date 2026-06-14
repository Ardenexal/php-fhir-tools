---
description: Internal design specifications, refactor plans, and roadmap.
icon: drafting-compass
---

# Design Notes & Roadmap

{% hint style="info" %}
These are internal engineering documents — specifications, refactor plans, and gap analyses.
They are not end-user documentation and may describe work that is planned, in progress, or
already shipped. Where a plan is complete, the code is authoritative.
{% endhint %}

## FHIRPath

### Component requirements specification

Source: `docs/component-guides/fhir-path.md`.

The requirements spec for the `ardenexal/fhir-path` library (`src/Component/FHIRPath/`). It
describes the FHIRPath 2.0 language features the evaluator targets — path navigation,
collection semantics, the function library, operators, the type system, and polymorphism — and
lays out the component's architecture (parser, evaluator, function registry, type resolver) and
a phased implementation plan.

### Gap analysis tracker

Source: `docs/component-guides/fhirpath-gap-analysis-tasks.md`.

Tracks the implementation against the FHIRPath 2.0 spec and the FHIR R4 FHIRPath extensions as
tasks #1–#42. **Status (per the doc, as of 2026-02-24): tasks #1–#32 complete; #33–#42 remain.**
The high-priority core spec functions (Groups 1–6) and the type-system namespace work (Group 7)
are all done. Remaining work is mostly FHIR-specific and behavioral/semantic gaps — Coding/
CodeableConcept equivalence (`~`), date/time arithmetic, and Quantity arithmetic. Verify current
progress with `composer test:fhir-path` and the spec suites.

## Serialization

### Normalizer refactor plan

Source: `docs/normalizer-refactor-plan.md` (tracking
[issue #59](https://github.com/Ardenexal/php-fhir-tools/issues/59)).

Plan to split `src/Component/Serialization/src/Normalizer/` into `Common/`, `Json/`, and `Xml/`
subfolders so a new serialization format needs only a new folder plus compiler-pass wiring,
without touching existing files. It also consolidates duplicated helpers into
`AbstractFHIRNormalizer`.

{% hint style="success" %}
**This refactor appears DONE in the code.** `src/Component/Serialization/src/Normalizer/`
already contains the planned `Common/` (`AbstractFHIRNormalizer`, `FHIRNormalizerInterface`),
`Json/`, and `Xml/` subfolders with the four Json and four Xml normalizers, and no old
normalizers remain at the `Normalizer/` root. Treat the plan as historical context.
{% endhint %}
