---
date: 2026-07-16
status: accepted
---

# ADR-012: SDC Service Decomposition — Per-Service Collaborators, and Why the Two Primitive Readers Stay Separate

**Status:** accepted
**Date:** 2026-07-16
**Milestone:** sdc-service-decomposition M01–M03

## Context

Both SDC service classes had grown past the gruff `size` error threshold (1000 lines):
`FHIRQuestionnairePopulateService` (1162) and `FHIRQuestionnaireResponseExtractService` (1094), each with
over-threshold-complexity methods. The `sdc-populate` backlog had *accepted* the cohesive ~1100-line files
as tolerable, but the `sdc-service-decomposition` plan decomposed both — behaviour-preserving only, guarded
by the SDC conformance corpora (R4/R4B/R5) + `Sdc` unit suite staying byte-identical (full suite `OK 3272`
at every step). This ADR records the collaborator split and the one non-obvious decision: the two services'
primitive readers are deliberately **not** unified.

## Decision 1 — Extract cohesive collaborators per service (not one shared god-helper)

**Decision:** decompose each service into single-responsibility `@internal` collaborators, injected via
optional trailing constructor params (BC-safe under the v1.0 freeze; the public `PopulateServiceInterface` /
`ExtractServiceInterface` are unchanged):

- **Populate** (1162 → 673 lines): `FhirPrimitiveReader` (stateless primitive→string reads),
  `AnswerValueCoercer` (the `coerceAnswerValue` type-dispatch table), `ObservationSelector` (the
  `observationLinkPeriod` selection/windowing cluster).
- **Extract** (1094 → 610 lines): `QuestionnaireResponseReader` (tolerant QR structural reads),
  `DefinitionExtractionWalker` (the definition-based extraction walk, composing the existing
  `DefinitionPathWriter`).

Both services now score gruff `size: A` / `complexity: A`. Two inherent-complexity methods relocated
**as-is** rather than being split to chase the score: `durationSeconds` (cyclomatic 29 — a UCUM unit table)
onto `ObservationSelector`, and the two definition-walk cognitive-error methods onto
`DefinitionExtractionWalker` (a genuine Long-Method reduction is backlogged, not forced into a
behaviour-preserving move).

## Decision 2 — The shared `FhirPrimitiveReader` is populate-only; the two services' primitive readers stay separate

The decomposition's original motivation included unifying the duplicated primitive-string readers across
the two services (`populate::stringify`/`codeOf` vs `extract::stringifyPrimitive`/`expressionString`).
Investigation showed the "duplicate" is **superficial** — they encode different contracts:

- `FhirPrimitiveReader::stringify` (populate) is permissive: it also stringifies `bool`/`int`/`float` and
  `__toString` objects.
- `QuestionnaireResponseReader::stringifyPrimitive` (extract) is strict: **only** string-valued inputs read;
  a non-string yields `null`.

At `DefinitionExtractionWalker::resolveFullUrl`, a `fullUrl` sub-expression is evaluated to an arbitrary
FHIRPath scalar and the strict `null`-for-non-string result drives extraction's `urn:uuid` fallback. Adopting
the permissive populate reader there would stringify a bool/int instead — a **behaviour change**, not a
refactor.

**Decision:** keep two separate primitive readers — `FhirPrimitiveReader` (populate, permissive) and
`QuestionnaireResponseReader::stringifyPrimitive` (extract, strict). They are NOT unified. A shared reader
was rejected because it cannot preserve extract's byte-identical behaviour at the FHIRPath-scalar boundary.

## Consequences

- **Positive:** both services are under the size gate with their own worst complexities cleared; each
  collaborator is single-responsibility, `@internal`, and unit-testable in isolation; the split is BC-safe.
- **Accepted:** the two services share *structure* (parallel decomposition) but not *primitive-reader code* —
  a small, deliberate divergence that keeps each service byte-identical. The relocated inherent-complexity
  methods (`durationSeconds`, the two walk methods) carry gruff findings on their new homes; these are
  documented advisory debt, not the exit bar (which is per-service: the *service* file/class under threshold).
- **Guardrail:** every step verified byte-identical against the full suite (`OK 3272`) — no expected-fixture
  edits across M01–M03.
