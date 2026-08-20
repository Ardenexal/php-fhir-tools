---
date: 2026-07-11
status: accepted
---

# ADR-010: SDC `$extract` Boundaries — Write Mechanism, StructureMap Deferral, Create/Update-Only, Provenance Shape

**Status:** accepted
**Date:** 2026-07-11
**Milestone:** sdc-extract M04 (Make It Solid — hardening + docs)

## Context

The `sdc-extract` plan (M01–M03) shipped observation-, definition-, and template-based
`QuestionnaireResponse/$extract` in `Ardenexal\FHIRTools\Component\Sdc\`. M04 hardens the edges and
records the load-bearing decisions that the code already embodies, so a cold-start reader understands
*why* the boundaries sit where they do. Four decisions are captured here.

## Decision 1 — Definition-path write mechanism: metadata-driven reflection, not hand-mapped setters

Definition-based extraction addresses a target element by a **flat canonical path**
(`Questionnaire.item.definition` = `…/Patient#Patient.name.family`) and must write an answer value into
the correct typed model property. A flat path carries neither cardinality nor type.

**Options considered:**

- **A — Hardcoded per-type setter map** (`Patient.name.family` → closure). Rejected: unbounded
  maintenance surface across R4/R4B/R5 × every resource; defeats the toolkit's generated-model premise.
- **B — Generic writer sourcing cardinality/type from the generated `#[FhirProperty]` metadata via
  reflection** (`DefinitionPathWriter` + `PropertyMetadataProvider`). Chosen.

**Chosen: Option B.** The writer walks the path segment by segment, reading `#[FhirProperty]`
(`isArray`, `propertyKind`, choice `variants`/`jsonKey`, declared `phpType`) to decide whether to
create/reuse an element, wrap a scalar into its declared primitive class, or resolve a choice slice
(`value[x]:valueQuantity`). This is the genuinely novel primitive the plan was built to prove (ISSUE.md);
it is version-generic because the metadata is generated per version. Consequence: extraction correctness
depends on the fidelity of the generated `#[FhirProperty]` metadata, which is already the toolkit's
serialization contract — no new source of truth is introduced.

## Decision 2 — StructureMap-based extraction is deferred

`questionnaire-targetStructureMap` (`extract-complex-smap`) requires a FHIR Mapping Language (FML)
engine to execute the StructureMap. The toolkit ships no FML interpreter, and building one is a large,
self-contained effort shared with StructureMap-based `$populate`.

**Decision:** defer. A Questionnaire carrying only `targetStructureMap` extracts nothing; the operation
does not fail. This is the largest deferred item and would be its own plan (see `sdc-extract/backlog.md`,
Maybe tier). `templateExtractBundle` (a `#contained` `Bundle` template, exercised by the upstream
`extract-complex-template2` example) is deferred on the same basis — no reference oracle vendored — and
is skipped with a `warning` diagnostic rather than emitting a malformed nested Bundle.

## Decision 3 — Extraction is create/update-only; it never deletes

Per [SDC extraction](https://build.fhir.org/ig/HL7/sdc/en/extraction.html), each transaction-Bundle
entry's `request` is `POST Type` (no logical `id`) or `PUT Type/id` (an `id` was written during
extraction). The service emits **only** these two directives.

**Decision:** extraction produces create/update transaction entries and **never** emits `DELETE`, and
never reconciles or removes data on a live server. The SDC "replace strategy" for definition-based
updates (all existing resource data must flow through the Questionnaire for a safe update) and any
fetch-current-then-merge round-trip are out of scope until a live-server story exists (backlog, Maybe).
The service returns the Bundle; persistence and access control are the caller's responsibility (it
performs no PHI permission filtering).

## Decision 4 — Provenance is opt-in; the agent is named textually

The optional `Provenance` entry (`ExtractContext(emitProvenance: true)`) must be cardinality-complete
R4/R4B/R5 FHIR: `target` 1..* (the extracted resources), `recorded` 1..1, `agent` 1..* (each with
`agent.who`), and `entity` (`role = source`, `what` → the QR). The SDC spec pins `entity`/`role = source`
but not the acting agent.

**Decision:** the agent is the extracting software, named **textually** via `agent.who.display`
("Ardenexal FHIR Tools — QuestionnaireResponse/$extract") rather than minting a synthetic `Device`
resource into the Bundle — the toolkit is an offline library with no stable Device identity, and an
invented Device entry would pollute the transaction. `recorded` is set from the current instant.
Provenance is **opt-in** and appended only when ≥1 resource was extracted (a `Provenance.target` is
1..*), so the default output stays byte-comparable against the existing oracle fixtures (no re-vendoring).
Its `target` references reuse each entry's already-resolved `fullUrl`, so the Provenance and the entries
it attests never diverge. Validated at runtime against the toolkit validator: `isValid: true`, 0 errors
(only the `dom-6` narrative best-practice warning, which applies to all domain resources).

## Consequences

- `DefinitionPathWriter` remains the single reflective write path; new element shapes are handled by
  extending metadata reads, not by adding type-specific code.
- StructureMap and `templateExtractBundle` cases surface diagnostics and skip; they are tracked in
  `backlog.md` with the upstream IG example that exercises each.
- The operation is safe to run without a server: it never deletes and never persists.
- `ExtractContext` gained one field (`emitProvenance`, default `false`); no existing caller behaviour
  changes.
