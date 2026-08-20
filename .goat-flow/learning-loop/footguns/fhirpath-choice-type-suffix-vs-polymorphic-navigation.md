---
category: fhirpath-choice-type-suffix-vs-polymorphic-navigation
last_reviewed: 2026-08-05
---

# Footguns: `%var.effectiveDateTime` silently resolves empty even when the raw resource has that exact field

## Footgun: an explicit choice-type suffix on a FHIRPath expression returned nothing where the polymorphic name worked

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground, post-M07 gallery addition)

Given a real `Observation` resource fetched live from `hapi.fhir.org` with a JSON field literally named
`effectiveDateTime: "2024-05-30"`, a Questionnaire `initialExpression` of `%obs.effectiveDateTime`
resolved to an empty value (the rendered field stayed blank) — no error, no warning, just silently
nothing. Switching to the polymorphic form `%obs.effective` (no `DateTime` suffix) resolved correctly to
`"2024-05-30"`.

Root cause not fully diagnosed (not worth the depth for a demo fixture), but the practical shape is clear:
this toolkit's FHIRPath evaluator did not match the explicit `[x]`-suffixed accessor
(`effectiveDateTime`) against a value that the JSON itself stores under that exact key name, while the
bare polymorphic property name (`effective`) did. This is the *opposite* of what FHIRPath's own spec
would suggest (the type-suffixed form is normally the more specific/reliable one) — worth being
suspicious of whenever a choice-typed (`[x]`) field expression silently returns nothing despite the raw
resource clearly having that field.

**Mitigation:** when a FHIRPath expression against a choice-typed (`[x]`) element returns unexpectedly
empty, try the bare polymorphic property name (e.g. `%obs.effective` instead of `%obs.effectiveDateTime`,
`%obs.value` instead of `%obs.valueQuantity`) before assuming the data itself is missing — confirmed
working in `demo/assets/sdc-samples/x-fhir-query-patient-scoped-demo.questionnaire.json`. If this
resurfaces on a non-demo/library task, it likely warrants a proper root-cause dig into the FHIRPath
component's choice-type property resolution rather than another workaround.
