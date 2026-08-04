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
- **`Questionnaire/$populate`** — implemented for expression-based population (`launchContext` +
  `initialExpression`, root/item `variable`, `itemPopulationContext` repeating groups) and
  observation-based population (`observationLinkPeriod`), R4 / R4B / R5. Delivered by the
  `sdc-populate` feature plan.

Shared prerequisites (see `.goat-flow/plans/sdc-foundation/`):

- The conformance oracle harness (`tests/Integration/AbstractSdcConformanceTest.php`) — a reusable
  test base that compares the **deserialized model** field-by-field against a frozen reference
  baseline, with an explicit ignore-list for spec-legal serialization divergence.

## `Questionnaire/$populate`

`FHIRQuestionnairePopulateService` pre-fills a `QuestionnaireResponse` from a `Questionnaire`'s SDC
population directives plus caller-supplied launch-context data, per the
[SDC populate operation](https://build.fhir.org/ig/HL7/sdc/en/populate.html). Call
`populate($questionnaire, new PopulateContext(...))`; the returned `PopulateResult` carries the generated
`QuestionnaireResponse` plus an optional companion `OperationOutcome`.

```php
$service = new FHIRQuestionnairePopulateService();
$result  = $service->populate($questionnaire, new PopulateContext(
    fhirVersion:            FhirVersion::R4,            // output model namespace (R4 / R4B / R5)
    launchContextResources: ['patient' => $patient],   // bound as FHIRPath %patient, %encounter, …
    subject:                'Patient/123',              // sets QuestionnaireResponse.subject (optional)
    dataProvider:           new BundlePopulationDataProvider($dataBundle), // observation-based (optional)
));
$response = $result->getResponse();    // a QuestionnaireResponse (status: in-progress)
$issues   = $result->getIssues();      // an OperationOutcome, or null when nothing to report
```

A canonical URL **string** may be passed instead of a Questionnaire object when the service is
constructed with a `FHIRQuestionnaireResolverInterface`; without one, a string argument yields an empty
QR plus a warning.

### Supported population mechanisms

| Mechanism | Directive | Notes |
|---|---|---|
| **Launch context** | `launchContext` + `initialExpression` | Each supplied resource is bound as `%<name>`; each item's `initialExpression` is evaluated and coerced to the item's answer type. |
| **Variables** | `variable` (root + item) | Resolved in declaration order into further `%<name>` constants; a multi-valued variable binds its first value (a warning records the truncation). |
| **Repeating groups** | `itemPopulationContext` | A group is emitted **once per context result**, with `%<name>` bound to each result for its descendants; nesting is supported. |
| **Observation-based** | `observationLinkPeriod` | Populates from the most-recent eligible `Observation` (status `final`/`amended`/`corrected`, matching `item.code`, within the link period) supplied via the `dataProvider`. |

### Behaviour contract

- **Offline-first.** All launch-context data and observations are supplied by the caller up front; the
  service performs no live FHIR fetching.
- **`enableWhen` is not applied.** Disabled items are still populated — the spec treats `enableWhen` as a
  display-time concern ("fill in as much data as possible, even if it may not always be needed").
- **Empty set = not answered.** An expression resolving to empty — a boolean included, and an empty
  string — produces **no** answer and an `information` issue, never a `false`/empty value.
- **Never throws.** A malformed expression, an unresolvable canonical URL, a missing launch context, an
  empty `itemPopulationContext`, or an item with no `linkId` each degrade to an `OperationOutcome` issue
  while the rest of the form still populates.
- **Answer coercion is strict-by-source-datatype.** Complex item types (`choice`/`quantity`/`reference`/
  `attachment`) require the expression to resolve to the right FHIR datatype object; a bare scalar for a
  complex item is a mismatch warning, not a silent coercion.

### Exclusions

- **StructureMap-based population** (`sourceStructureMap`) — requires a FHIR Mapping Language engine,
  which the toolkit does not ship. Deferred (see `sdc-populate/backlog.md`).
- **Live data fetching** — `x-fhir-query`, `dataEndpoint`, server-side `data` retrieval. Supply a
  `dataProvider` instead.
- **CQL expressions** (`text/cql`) — FHIRPath only; a non-FHIRPath language surfaces a warning and is
  skipped.
- **`calculatedExpression`** — continuous re-population as source answers change.
- **`candidateExpression` / `contextExpression` / `answerExpression`** — interactive answer-selection,
  a UI concern.
- **`populatehtml` / `populatelink`** — HTML/link rendering (UI concern; not deprecated — current in
  SDC v4.0.0).
- **Binding-driven `code`→`Coding` promotion** — a bare code for a choice item is not promoted to a
  systematised `Coding` via the item's value-set binding (tracked in `sdc-populate/backlog.md`).
- **Generated SDC profile classes** (Populatable/Extractable Questionnaire, SDC QuestionnaireResponse) —
  the engine reads extensions by URL and needs no profile classes; profile conformance is a `Validation`
  concern.
- **Generated typed SDC extension classes** — the engine reads extensions by URL via `SafeExtensionReader`
  (version-drift robust); typed extension classes are an optional authoring layer only (see `backlog.md`).
- **Access control / PHI authorization** — this offline library populates from whatever context the
  caller supplies and performs no permission filtering. The SDC spec's "SHALL NOT populate data the user
  is not permitted to access" is a caller responsibility.

For the full design rationale and boundary decisions, see
`.goat-flow/learning-loop/decisions/ADR-011-sdc-populate-boundaries.md`.

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
