# FHIR SDC

Structured Data Capture (SDC) operations for the FHIR Tools toolkit.

This component implements the SDC operations:

- **`Questionnaire/$populate`** — pre-fill a `QuestionnaireResponse` from launch context and
  expression-based population directives.
- **`QuestionnaireResponse/$extract`** — extract FHIR resources (definition-based and
  template-based) from a completed `QuestionnaireResponse`.

**Namespace:** `Ardenexal\FHIRTools\Component\Sdc\`

## Status

- **`QuestionnaireResponse/$extract`** — implemented for observation-, definition-, and template-based
  extraction (see below). Delivered by the `sdc-extract` feature plan.
- **`Questionnaire/$populate`** — delivered by the `sdc-populate` feature plan.

Shared prerequisites (see `.goat-flow/plans/sdc-foundation/`):

- The conformance oracle harness (`tests/Integration/AbstractSdcConformanceTest.php`) — a reusable
  test base that compares the **deserialized model** field-by-field against a frozen reference
  baseline, with an explicit ignore-list for spec-legal serialization divergence.

## `QuestionnaireResponse/$extract`

`FHIRQuestionnaireResponseExtractService` turns a completed `QuestionnaireResponse` into FHIR
resources per the [SDC extraction operation](https://build.fhir.org/ig/HL7/sdc/en/extraction.html).
Call `extract($questionnaireResponse, new ExtractContext(...))`; the returned `ExtractResult` carries
the extracted payload plus an optional companion `OperationOutcome`.

```php
$service = new FHIRQuestionnaireResponseExtractService();
$result  = $service->extract($questionnaireResponse, new ExtractContext(
    fhirVersion:    FhirVersion::R4,   // output model namespace (R4 / R4B / R5)
    questionnaire:  $questionnaire,    // the source Questionnaire carrying the extract directives
    emitProvenance: false,            // opt-in Provenance entry (see below)
));
$bundle = $result->getResource();      // a transaction Bundle (always)
$issues = $result->getIssues();        // an OperationOutcome, or null when nothing to report
```

### Supported extraction methods

| Method | Directive | Versions | Notes |
|---|---|---|---|
| **Observation-based** | `observationExtract` | R4 only | One `Observation` per answer under a flagged item. A non-R4 run surfaces a `warning` and skips (it builds R4 datatypes directly). |
| **Definition-based** | `definitionExtract` / `definitionExtractValue` | R4 / R4B / R5 | Hierarchical writes down the item tree; `extractAllocateId` cross-references; `fixed-value` + FHIRPath calculated values; choice slices. |
| **Template-based** | `templateExtract` / `templateExtractContext` / `templateExtractValue` | R4 / R4B / R5 | Clone a `#contained` template, one instance per matching QR item; focus-shift fan-out; the `fullUrl` slice. |

### Output contract

- The payload is **always** a `transaction` **Bundle**, even for a single extracted resource.
- Each `entry.request` is derived from the resource's logical `id`: no id → `POST Type` (create);
  an id → `PUT Type/id` (update). Extraction is **create/update only** — it never deletes.
- Each `entry.fullUrl` is the allocated `urn:uuid:` (when a `fullUrl` expression resolved one) or a
  freshly-minted `urn:uuid:` otherwise.
- A single Questionnaire may mix all three methods; the results merge into **one** transaction Bundle.
- **Empty / no-op extraction** yields an empty Bundle plus an `information` `OperationOutcome`
  ("No resources were extracted") — not an error.
- A **malformed extraction expression** surfaces a `warning` issue and skips that entry; the run
  continues (holds for the definition and template paths alike).

### Provenance (opt-in)

With `ExtractContext(emitProvenance: true)`, and when at least one resource was extracted, the Bundle
gains a `Provenance` entry: `target` references every extracted resource by its `fullUrl`, `entity`
(`role = source`) references the source `QuestionnaireResponse`, and the required `recorded` +
`agent.who` are populated (the agent is the toolkit software, named textually — see
`.goat-flow/learning-loop/decisions/ADR-010-sdc-extract-boundaries.md`). Default output omits Provenance so it stays
oracle-comparable.

### Exclusions

- **StructureMap-based extraction** (`questionnaire-targetStructureMap`) — requires a FHIR Mapping
  Language engine, which the toolkit does not ship. Deferred (see `sdc-extract/backlog.md`).
- **`templateExtractBundle`** (a `#contained` `Bundle` template) — skipped with a `warning`; no
  reference oracle vendored yet. Deferred.
- **Live persistence** — the service returns the transaction Bundle for the caller to persist; it does
  not POST it to a FHIR server.
- **Access control / PHI authorization** — this offline library extracts whatever the supplied
  `QuestionnaireResponse` contains and performs no permission filtering. The SDC spec's "SHALL NOT
  extract data the user is not permitted to access" is a caller responsibility.

## Conformance oracle

Golden expected outputs must be vendored from a recognized SDC reference implementation (never
hand-authored) and frozen as the seeded baseline — see `tests/SOURCES.md` for the reference impl,
its version, and known fidelity caveats.
