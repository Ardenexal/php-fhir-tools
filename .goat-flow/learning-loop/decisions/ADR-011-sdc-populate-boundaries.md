---
date: 2026-07-13
status: accepted
---

# ADR-011: SDC `$populate` Boundaries — Offline-First, FHIRPath-Only, StructureMap Deferral, Component Placement, PHI Responsibility

**Status:** accepted
**Date:** 2026-07-13
**Milestone:** sdc-populate M03 (Make It Solid — edges, errors, docs)

## Context

The `sdc-populate` plan (M01–M03) shipped expression- and observation-based `Questionnaire/$populate`
in `Ardenexal\FHIRTools\Component\Sdc\` (`FHIRQuestionnairePopulateService`). M03 hardens the edges and
records the load-bearing decisions the code already embodies, so a cold-start reader understands *why*
the boundaries sit where they do. It mirrors ADR-010 (the `$extract` boundaries) — the two SDC operations
share the offline-first, FHIRPath-only, StructureMap-deferred, PHI-caller-responsibility posture. Five
decisions are captured here.

## Decision 1 — Population is offline-first; no live `x-fhir-query` / `dataEndpoint`

`$populate` can, per spec, fetch population data from a live FHIR server (`x-fhir-query`, `dataEndpoint`,
`sourceQueries`). The toolkit is an offline library with no FHIR search client.

**Decision:** the caller supplies **all** data up front — launch-context resources (bound as FHIRPath
external constants `%patient`, …) and candidate `Observation`s via a named `PopulationDataProviderInterface`
seam (`BundlePopulationDataProvider` wraps a pre-fetched `Bundle`). No live fetching happens inside the
library. A future live-fetch provider can implement the same interface without a breaking change to
`FHIRQuestionnairePopulateService` or `PopulateContext`.

**Consequence — corpus-coverage bias (recorded, not a gap to close).** Every published SDC-IG populate
example Questionnaire (`Questionnaire-CardiologyForm`, `Questionnaire-rxterms`,
`StructureMap-questionnaire-population-transform`) drives population via a mechanism this engine does not
implement — `x-fhir-query`, `calculatedExpression`, `answerExpression`, or StructureMap — so **none** can
serve as an oracle case for the offline-first / FHIRPath-only feature set. The mechanisms the toolkit does
implement are therefore proven against **authored-input + reference-engine-vendored-output** oracles
(forms-lab; the seven `populate-*` fixtures), not against IG example forms. The corpus skews toward
simpler forms as a direct result of this boundary. Full triage: `src/Component/Sdc/tests/SOURCES.md`
("`$populate` M03 — Conformance corpus finalisation").

## Decision 2 — Expression evaluation is FHIRPath-only; CQL is deferred

`initialExpression` / `variable` / `itemPopulationContext` may carry `text/fhirpath`, `text/cql`, or
`application/x-fhir-query`. The toolkit ships a FHIRPath engine and no CQL engine.

**Decision:** only `text/fhirpath` (and an unset language, which defaults to FHIRPath) is evaluated. An
expression declaring any other language surfaces a `warning` `OperationOutcome` issue and is skipped —
never silently ignored, never a hard error. CQL is deferred until a CQL engine exists in the toolkit
(`sdc-populate/backlog.md`, Later); FHIRPath covers the common population cases.

## Decision 3 — StructureMap-based population is deferred

`sdc-questionnaire-sourceStructureMap` requires a FHIR Mapping Language (FML) engine to execute the
StructureMap. The toolkit ships no FML interpreter, and building one is a large, self-contained effort
**shared with StructureMap-based `$extract`** (ADR-010, Decision 2).

**Decision:** defer. This is the largest single deferred item and would be its own plan
(`sdc-populate/backlog.md`, Maybe tier). It is tracked alongside the deferred interactive/continuous
modes — `calculatedExpression` (continuous re-population), and `candidateExpression` / `contextExpression`
/ `answerExpression` (interactive answer-selection, a UI concern a headless library cannot drive).

## Decision 4 — `SafeExtensionReader` lives in `Metadata`; the Questionnaire resolver stays in `Validation` (depended on, not moved)

Population reads SDC extensions from deserializer-origin objects via `SafeExtensionReader`, and resolves a
canonical Questionnaire URL via `FHIRQuestionnaireResolverInterface`. Both are pre-existing cross-component
seams, not new to this plan.

**Decision:** `SafeExtensionReader` lives in `src/Component/Metadata/src/` — the toolkit's home for any
interface or attribute visible to more than one component (per CLAUDE.md's Metadata rule); the `Sdc`
component depends on it rather than owning a private copy. The Questionnaire **resolver** interface stays
in `Validation` (its original home, ADR-007) and is **depended on, not relocated** to `Sdc` or `Metadata`:
moving a public interface during the active `v1.0-release` API freeze would be a BC break. `Sdc` takes an
optional `FHIRQuestionnaireResolverInterface` in its constructor (null-object-friendly: absent resolver →
a string `$questionnaire` yields an empty QR plus a warning). Consequence: the dependency direction is
`Sdc → Validation` for the resolver and `Sdc → Metadata` for extension reading — no new shared component
and no interface relocation.

## Decision 5 — Access control / PHI authorization is a caller responsibility

The SDC spec states an implementation "SHALL NOT populate data the user is not permitted to access."

**Decision:** this offline library populates from whatever launch-context resources and data the caller
supplies and performs **no** permission filtering. Deciding what a given user may see, and pre-filtering
the supplied context accordingly, is the caller's responsibility — the same posture as `$extract`
(ADR-010, Decision 3). This keeps the library free of an authorization model it has no basis to implement.

## Consequences

- Population is safe to run without a server: it fetches nothing and persists nothing.
- Non-FHIRPath languages, `x-fhir-query`, `calculatedExpression`, answer-selection expressions, and
  StructureMap population each surface a diagnostic and skip; all are tracked in `backlog.md` with the
  upstream IG example that exercises each (`SOURCES.md` M03 triage).
- The conformance corpus is intentionally biased toward simpler forms; this is documented, not a defect.
- No public interface moved: `Sdc` depends on `Validation`'s resolver and `Metadata`'s `SafeExtensionReader`,
  preserving the v1.0 API freeze.
- PHI/authorization is explicitly out of scope and documented in both the component README and `ISSUE.md`.
