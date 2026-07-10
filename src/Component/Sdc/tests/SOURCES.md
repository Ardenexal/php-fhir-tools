# Conformance Oracle Sources

Golden expected outputs for SDC `$populate`/`$extract` conformance MUST be vendored from a
recognized reference implementation and frozen as the seeded baseline — never hand-authored
(see the `questionnaire-conformance-seed-truth` discipline).

## Reference implementation runnability (proven — M00 spike)

| Field | Value |
|-------|-------|
| Engine | `sqlonfhir` |
| Version | `4.0.16.0` |
| Publisher | Telstra Health |
| FHIR version | 4.0.0 (R4) |
| Role | Hosted forms backend for fhirpath-lab |
| Endpoint | `https://sqlonfhir-r4.azurewebsites.net/fhir` |
| Operation proven | `POST Questionnaire/{id}/$populate` (instance-level) → HTTP 200 `QuestionnaireResponse` |
| Captured | 2026-07-09 |

The M00 reference-runnability spike confirmed a live SDC reference implementation is reachable and
produces a captured `QuestionnaireResponse`, satisfying the oracle-viability kill criterion.

## Known fidelity caveats (READ BEFORE SEEDING GOLDEN OUTPUTS)

`sqlonfhir 4.0.16` proved the oracle **mechanics** but is **not** trustworthy as the golden-seed
engine as-is:

- **Type-level `$populate`** (inline `questionnaire` in `Parameters`) → `NullReferenceException`.
  Only **instance-level** (`Questionnaire/{id}/$populate`, questionnaire stored first) works.
- **`local: true`** → `NullReferenceException`.
- **`initialExpression` is not evaluated** — a basic instance-level populate returns the
  `QuestionnaireResponse` skeleton with **no answers**.
- The R4 SDC `launchContext` `name` sub-extension must use `valueId` (not `valueCoding`) on this server.

**Action for the feature plans (`sdc-populate` / `sdc-extract`):** before freezing any golden
output, select and validate a fuller reference engine (e.g. HAPI or Firely — reachable via the
fhirpath-lab UI, or a local Java HAPI install) that actually evaluates population/extraction
expressions. Record the engine that produced the frozen bytes here, with its real name + version.

## `sdc-extract` M01 — vendored artifacts

### Definition-based extraction input (M02 writer probe) — VENDORED

| Field | Value |
|-------|-------|
| File | `tests/Fixtures/Extract/extract-complex-defn3.questionnaire.json` |
| Source | HL7 SDC IG, `input/resources/extract-complex-defn3.json` (`HL7/sdc`, `master`) |
| URL | `https://raw.githubusercontent.com/HL7/sdc/master/input/resources/extract-complex-defn3.json` |
| Captured | 2026-07-09 |
| Role | **Input only** (a definition-based extraction Questionnaire). Not an expected output. Kept early so M02's generic writer faces the real path shapes, not a synthetic probe. |

### Observation-based extraction oracle — VENDORED (M01 exit criterion 3 met)

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/observation-extract-basic.questionnaire.json` (authored — inputs may be authored) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/observation-extract-basic.response.json` (authored) |
| Expected Bundle | `tests/Fixtures/Extract/observation-extract-basic.expected-bundle.json` (**frozen reference output**) |
| Engine | **fhirpath-lab / forms-lab** (Brian Postlethwaite) — the `forms-lab` extract engine fhirpath-lab drives by default; supports observation-based extraction (HAPI does not expose `$extract`; `sqlonfhir` untrustworthy). |
| Endpoint | `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` |
| Request | FHIR `Parameters` with `questionnaire-response` + `questionnaire` (the shape fhirpath-lab's `QuestionnaireExtractTest.vue` posts). |
| Response | Returned the transaction `Bundle` **directly** (not wrapped in `Parameters`). Frozen verbatim. |
| Captured | 2026-07-10 |

**Documented divergences** (tolerated in `FHIRExtractConformanceTest`, NOT the shared harness):

1. **`Observation.issued` timezone** — forms-lab serialises the `instant` with an explicit `+00:00`
   offset; this toolkit uses `Z`. Identical instant, spec-legal serialization difference → normalised
   (`+00:00`/`-00:00` → `Z`) before comparison. The instant *value* is still compared (a wrong time,
   or a missing `issued`, fails). **Note:** seeding against this oracle **caught a real gap** — the
   service originally omitted `Observation.issued`; SDC extraction.html (line ~274) mandates
   `issued ← QR.authored`, now implemented.
2. **`entry.request.url`** — forms-lab emits only `request.method`; this toolkit also emits
   `request.url: "Observation"` (required by FHIR for a transaction POST, verified in the unit test).
   The harness's own contract compares `request.method`, so `url` is added to this subclass's
   `IGNORED_KEYS` only (adding it to the shared list would collapse `extension.url` and gut `$populate`).

`FHIRExtractConformanceTest::testOracleComparisonDetectsSemanticDifferences` proves the ignore-list +
normalisation did not make the comparison vacuous (a mutated `code` still fails).

### Definition-based extraction oracle (M02) — VENDORED (probe)

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/definition-extract-basic.questionnaire.json` (authored — a `Patient.name` group + `given`/`family`/`birthDate`) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/definition-extract-basic.response.json` (authored) |
| Expected Bundle | `tests/Fixtures/Extract/definition-extract-basic.expected-bundle.json` (frozen forms-lab output) |
| Engine / Endpoint | fhirpath-lab / forms-lab — `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` (no `model` param needed) |
| Captured | 2026-07-10 |

**Finding (drives M02 design):** definition-based extraction is **hierarchical** — a group item whose
`definition` is `Patient.name` yields ONE `name` element; its children (`Patient.name.given`,
`.family`) populate that instance (`name:[{family, given:[…]}]`). Flattening the paths as siblings
makes forms-lab emit a **separate `name` per path**. The M02 service must follow the item tree with a
per-group write context, not write absolute paths independently. Unlike observation-based, forms-lab
**does** emit `entry.request.url` here.

### `extractAllocateId` cross-reference oracle (M02) — VENDORED

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/definition-extract-allocateid.questionnaire.json` (authored — a root `extractAllocateId` "NewPatientId"; a `Patient` group with a `fullUrl` sub-expression `%NewPatientId`; a `RelatedPerson` group with a `definitionExtractValue` writing `%NewPatientId` into `RelatedPerson.patient.reference`) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/definition-extract-allocateid.response.json` (authored) |
| Expected Bundle | `tests/Fixtures/Extract/definition-extract-allocateid.expected-bundle.json` (frozen forms-lab output) |
| Engine / Endpoint | fhirpath-lab / forms-lab — `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` (no `model` param) |
| Captured | 2026-07-10 |

**Finding (proves the M02 kill criterion):** `extractAllocateId` allocates a UUID and binds it to a
FHIRPath **external constant** (`%NewPatientId`). The bound value is the **full `urn:uuid:<uuid>`
string** (not the bare UUID) — proven because the Patient's `fullUrl` (from the `fullUrl`
sub-expression `%NewPatientId`) and the `RelatedPerson.patient.reference` (from a `definitionExtractValue`
expression `%NewPatientId`) are **byte-identical**. `request.method` stays **POST** even with an
allocated `fullUrl` — allocateId does not imply `PUT`. Two extracted resources therefore point at each
other via the same `urn:uuid:`. The `FHIRExtractConformanceTest` proves this three ways: an oracle
comparison, a **direct** equality assertion on our own output (`entry[0].fullUrl === entry[1].resource.patient.reference`,
independent of the oracle's random UUIDs), and a linkage-mutation guard (breaking the reference makes
the oracle comparison fail, so `tokenizeUuids` verifies topology, not mere `urn:uuid:` presence).
