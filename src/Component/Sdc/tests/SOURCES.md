# Conformance Oracle Sources

Golden expected outputs for SDC `$populate`/`$extract` conformance MUST be vendored from a
recognized reference implementation and frozen as the seeded baseline — never hand-authored
(see the `questionnaire-conformance-seed-truth` discipline).

## `$extract` cross-version oracle status (M02 — R4/R4B/R5 parity)

The forms-lab extract engine's capability statement declares **`fhirVersion: 4.3.0` (R4B)**
(captured `GET https://fhir.forms-lab.com/metadata`, 2026-07-11). Consequences for the vendored
definition-based `$extract` oracles:

- **R4B — genuine independent oracle.** The frozen `definition-extract-*.expected-bundle.json`
  Bundles were produced by an R4B-native engine, so they *are* R4B oracles. `FHIRExtractConformanceTest::testDefinitionCorpusR4BConformsToReferenceOracle`
  drives each case through the R4B model namespace against them.
- **R4 — same bytes, wire-compatibility.** R4 (4.0.1) and R4B (4.3.0) are wire-compatible for the
  resources these cases exercise (Patient/RelatedPerson name/identifier/reference), so the R4 cases
  reuse the identical frozen Bundles.
- **R5 — structural parity, NOT an independent oracle.** No independent SDC `$extract` engine for
  R5 is reachable (forms-lab is R4B; HAPI exposes no `$extract`; sqlonfhir is untrustworthy).
  `testDefinitionCorpusR5StructurallyMatchesReferenceOracle` asserts the R5 model path yields a
  Bundle **structurally equivalent** to the frozen R4B/R4 oracle — legitimate only because the
  extracted resources and the transaction envelope are byte-identical across R4→R4B→R5 for these
  cases. This is a documented M02 deviation; reseed from a real R5 engine if one becomes available.
- **Observation-based extraction stays R4-only** (M01 scope). The version-generic service emits a
  warning `OperationOutcome` issue for any `observationExtract` item under a non-R4 run rather than
  producing a wrong-version Observation.

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

### Typed `definitionExtractValue` oracle (M02) — VENDORED

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/definition-extract-value.questionnaire.json` (authored — a `Patient` group; a `mrn` leaf answering `Patient.identifier.value` and carrying a `definitionExtractValue` writing the FHIRPath literal `'http://example.org/mrn'` into `Patient.identifier.system`) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/definition-extract-value.response.json` (authored) |
| Expected Bundle | `tests/Fixtures/Extract/definition-extract-value.expected-bundle.json` (frozen forms-lab output) |
| Engine / Endpoint | fhirpath-lab / forms-lab — `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` (no `model` param) |
| Captured | 2026-07-10 |

**Finding:** a calculated `definitionExtractValue` merges with an answered sibling into **one** element
instance — forms-lab emits `identifier: [{system, value}]`, not two separate identifiers. The calculated
`system` (a `?UriPrimitive`) is written from a FHIRPath string literal. This drove the
`DefinitionPathWriter` scalar→primitive coercion: a raw scalar result is wrapped into the target
property's declared primitive class (reflection-based, no hardcoded `fhirType` map), while a union type
that already accepts the scalar (`StringPrimitive|string` for `identifier.value`) is left raw. A
malformed expression surfaces a warning `OperationOutcome` issue rather than silently vanishing
(`FHIRQuestionnaireResponseExtractServiceTest::testMalformedDefinitionExtractValueExpressionReportsIssueWithoutCrashing`).

### `extract-complex-defn3` full-form oracle (M02 stretch) — VENDORED

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/extract-complex-defn3.questionnaire.json` (HL7 SDC IG, vendored M01) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/extract-complex-defn3.response.json` (**authored** — the IG ships none; carries `id`, `authored`, `author` so the temporal/derivedFrom/performer calculated values are deterministic) |
| Expected Bundle | `tests/Fixtures/Extract/extract-complex-defn3.expected-bundle.json` (frozen forms-lab output, verbatim) |
| Engine / Endpoint | fhirpath-lab / forms-lab (R4B-native, 4.3.0) — `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` (no `model` param) |
| Captured | 2026-07-11 |

**Findings (drove the M02 stretch — choice/complex calculated-value coercion):**

1. **`definitionExtractValue` has two value sources** — a FHIRPath `expression` (M02) **and** a
   `fixed-value` sub-extension carrying a literal (`code`/`uri`/`Coding`/`CodeableConcept`). The service
   now reads both. Complex fixed values (`code.coding`←`Coding`, `identifier.type`←`CodeableConcept`)
   flow through the writer's array/complex paths.
2. **Choice-slice `value[x]:valueQuantity`** — the `DefinitionPathWriter` resolves the slice to its
   variant class via `#[FhirProperty]` variant metadata (`jsonKey`), creating/reusing the `Quantity`
   the child `.value`/`.unit` leaves populate.
3. **A `definitionExtract` root can itself be an answer leaf** — `height`/`weight` are Observation roots
   whose own `definition` is `value[x]:valueQuantity.value` with a decimal answer; that answer is now
   written to the resource (previously dropped).
4. **Coded answer → code leaf** — a `Coding` answer for `Patient.gender` (a `code`) is reduced to its
   `.code` (`"male"`). **Temporal coercion** — a computed datetime string is `parse()`d into the leaf's
   FHIR value-object (`Observation.issued` = `InstantPrimitive(FHIRInstant)`), and a choice `effective[x]`
   scalar is wrapped into its declared `dateTime` variant.
5. **`complication` is NOT its own Observation** — it carries `definitionExtractValue`s but no
   `definitionExtract` flag, so forms-lab yields **4 entries** (Patient, RelatedPerson, 2 Observations),
   not 5.

**Focus-node `definitionExtractValue` — `Patient.name.text` (RESOLVED M05):** the `name` group's
`definitionExtractValue` expression
`item.where(linkId='given' or linkId='family').answer.value.join(' ')` requires the **current QR item**
as the FHIRPath focus while `%resource` stays the QR root. The FHIRPath evaluator now models this
focus/`%resource` split (`EvaluationContext::withResourceNode` + the `%context` vs `%resource`
resolution in `FHIRPathEvaluator::resolveEnvironmentVariable`; wired in
`FHIRQuestionnaireResponseExtractService` — see `M05-fhirpath-focus-context.md`), so defn3 now
reproduces `name.text = "Peter Chalmers"`. **`HumanName.text` is a data-bearing element the reference
engine computed on purpose — NOT narrative**, and though the oracle comparison drops `text` via the
shared `IGNORED_KEYS`, it is now asserted directly:
`FHIRExtractConformanceTest::testComplexDefn3ExtractsTypedResources` (R4B) and
`testComplexDefn3ComputesNameTextAcrossVersions` (R4B & R5). defn3 is now a clean end-to-end pass.

### `POST`/`PUT` request directive (M02) — VENDORED

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/definition-extract-put.questionnaire.json` (authored — a `Patient` group with a hidden `Patient.id` item + a `Patient.name.family` leaf) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/definition-extract-put.response.json` (authored — `id = pat-42`, `family = Chalmers`) |
| Expected Bundle | `tests/Fixtures/Extract/definition-extract-put.expected-bundle.json` (frozen forms-lab output) |
| Engine / Endpoint | fhirpath-lab / forms-lab — `POST https://fhir.forms-lab.com/QuestionnaireResponse/$extract` (no `model` param) |
| Captured | 2026-07-10 |

**Finding:** per [extraction.html](https://build.fhir.org/ig/HL7/sdc/en/extraction.html), *"if the resource
has no id property set the value to POST … otherwise set the value to PUT"* — url is the resource type
for a create (`Patient`) or `Type/id` for an update (`Patient/123`); the trigger is the presence of a
logical `id` (set via a hidden item or `definitionExtractValue`), **not** any explicit extension. The
forms-lab probe **confirmed this end-to-end**: given a hidden `Patient.id = pat-42`, it returned
`request.method: PUT`, `request.url: Patient/pat-42`, and a fresh `urn:uuid:` `fullUrl` (the spec sets
`fullUrl` from the expression regardless of create-vs-update). `extract-complex-defn3`, the richest IG
example, only exercises the POST branch (`fullUrl: %NewPatientId`, no logical id), so this case was
authored to reach PUT. The harness compares `request.method` (not `url`), so
`FHIRExtractConformanceTest::testDefinitionExtractPutConformsToReferenceOracle` independently verifies
the `PUT` directive against the oracle; the exact `url = Patient/pat-42` and `resource.id` (both dropped
by the ignore-list) are asserted directly in `testDefinitionExtractWithLogicalIdProducesPutDirective`,
with `testDefinitionExtractWithoutLogicalIdStaysPost` guarding the id-less create → POST branch.

**The deserialized-fixture integration test caught a real bug the programmatic unit test masked:** a
deserialized answer arrives as a `StringPrimitive`, which cannot assign to the bare `?string`
`Patient.id` leaf — fixed by `DefinitionPathWriter::unwrapForBuiltinLeaf` (see footgun
`model-object-initialization`, "bare builtin-scalar leaves reject deserializer-wrapped primitive answers").

## `sdc-extract` M03 — Template-based extraction oracle

### `extract-complex-template` template oracle (M03) — VENDORED

| Field | Value |
|-------|-------|
| Input Questionnaire | `tests/Fixtures/Extract/extract-complex-template.questionnaire.json` (the SDC IG `extract-complex-template` example — 5 `contained` templates: `patTemplate`, `rpTemplate`, `obsTemplateHeight`, `obsTemplateWeight`, `obsTemplate`; `extractAllocateId = "NewPatientId"`; the Patient `templateExtract` carries a `fullUrl` slice = `%NewPatientId`, no `resourceId` → POST) |
| Input QuestionnaireResponse | `tests/Fixtures/Extract/extract-complex-template.response.json` (authored minimal answers: Carlos Ramirez / male / IHI `92304872038472` / height 173 / weight 110 / complication false; **no `id`, `authored`, or `author`** — so `%resource.id`/`.authored`/`.author` are empty, exercising context-empty removal and static-value retention) |
| Expected Bundle | `tests/Fixtures/Extract/extract-complex-template.expected-bundle.json` (frozen `@aehrc/sdc-template-extract` output, verbatim) |
| Engine | **`@aehrc/sdc-template-extract` v1.0.15** (AEHRC / CSIRO — `aehrc/smart-forms`, `packages/sdc-template-extract`). Independent JS/TS reference implementation of SDC template-based `$extract`. HAPI/forms-lab do **not** implement template-based extraction, so this is the only reachable independent oracle for it. |
| Captured | 2026-07-11 (bundle generated by running the engine on the vendored Q + QR) |

**What the case exercises:** `templateExtract` (clone a `#contained` template, one instance per matching
QR item by linkId), `templateExtractContext` (focus-shift + fan-out — one element clone per context
result; empty context → element removed, e.g. `derivedFrom` via empty `%resource.id`),
`templateExtractValue` (evaluate → set, empty → keep any static value / drop the wrapper, N → replicate,
e.g. `name.given`), the `fullUrl` slice, `%NewPatientId` cross-references (Patient `fullUrl` ==
RelatedPerson `patient.reference` == each Observation `subject`), a `Coding` value result reduced to
`{system,code,display}` (`relationship`), and numeric coercion (`answer.value * 100` → 17300).

**Fidelity caveats (reconciled in `FHIRExtractConformanceTest`, NOT masked):**

1. **Malformed `Observation.subject`** — the engine writes the `templateExtractValue` result directly onto
   `subject`, emitting a **bare string** (`"subject": "<uuid>"`) rather than a `Reference`. That is invalid
   FHIR and our typed deserializer rejects it (proven). This toolkit emits a valid `subject: {reference: <uuid>}`;
   the extract conformance subclass unwraps a sole-key `{reference: X}` → `X` on **both** sides before
   comparison so the reference topology still matches.
2. **Bare-uuid vs `urn:uuid:` `fullUrl`** — the engine emits the Patient `fullUrl` as a **bare** uuid (raw
   `%NewPatientId`) while minting `urn:uuid:` for the other entries; this toolkit uses `urn:uuid:`
   throughout (shared with M02 `extractAllocateId`). The subclass normalises bare uuids → `urn:uuid:` on
   both sides up front (which also keeps `sortKey()` entry ordering aligned) before the existing
   `tokenizeUuids` collapses the topology to positional tokens.
3. **Bundle `meta.tag` + `timestamp`** — the `@aehrc/sdc-template-extract:generated` provenance tag and the
   generation `timestamp` are engine-specific/non-deterministic; the subclass drops Bundle-level `meta`
   and `timestamp` before comparison.

## `sdc-extract` M04 — Conformance corpus finalisation (full SDC-IG triage)

Authoritative upstream list captured `2026-07-11` from
`gh api repos/HL7/sdc/contents/input/resources?ref=master` (every `input/resources` entry matching
`extract`). Every SDC-IG `$extract` example is triaged below — covered by a vendored oracle, or deferred
with a reason and a `backlog.md` pointer. No SDC-IG example is silently unaccounted for.

| SDC-IG example (`HL7/sdc` `input/resources/`) | Method | Status |
|---|---|---|
| `extract-complex-defn3.json` | definition | **Covered** — M02, vendored forms-lab oracle (`extract-complex-defn3.expected-bundle.json`), R4/R4B/R5. |
| `extract-complex-template.json` | template | **Covered** — M03, vendored `@aehrc/sdc-template-extract` oracle (`extract-complex-template.expected-bundle.json`). |
| `extract-complex-template2.json` | template (`templateExtractBundle`, a `#contained` `Bundle` template) | **Deferred** — Bundle templates are skipped with a `warning` diagnostic (`TemplateExtractor::extractTemplate`). No `templateExtractBundle` oracle vendored yet. → `backlog.md` (Next). |
| `extract-complex-smap.json` (+ `StructureMap-extract-complex-smap.xml`) | StructureMap (`targetStructureMap`) | **Deferred** — requires a FHIR Mapping Language engine, absent from the toolkit. Largest deferred item. → `backlog.md` (Maybe). |

**Non-IG oracle (no SDC-IG example exists):** observation-based extraction is proven against an
**authored** forms-lab oracle (`observation-extract-basic.*`) — the SDC IG ships no observation-based
`$extract` example, so the input QR/Questionnaire were authored and only the expected Bundle is vendored
(see the M01 observation oracle entry above).

**Cross-method merge (no vendorable oracle):** a single Questionnaire mixing observation-, definition-,
and template-based extraction (`extract-mixed-methods.*`) is a **hand-authored composition test**, not a
conformance oracle — no reachable engine implements all three methods, and each method's fidelity is
already oracle-proven separately, so `FHIRQuestionnaireResponseExtractServiceTest::testMixedMethodQuestionnaireYieldsOneMergedBundle`
tests only the service's *merge* into one transaction Bundle. This is the sanctioned exception to the
vendor-only rule (the `questionnaire-conformance-seed-truth` discipline), documented here because it is a
hand-authored fixture.

**Provenance (opt-in, `ExtractContext::$emitProvenance`):** the emitted `Provenance` is asserted by
field checks (`testProvenanceEntryEmittedWhenRequested`) and its cardinality was runtime-validated via the
toolkit validator (`isValid: true`, 0 errors; only the `dom-6` narrative best-practice warning). It is
**never** part of a byte-compared oracle fixture — the existing oracle Bundles stay Provenance-free so
they need no re-vendoring.

---

## `$populate` conformance oracle (sdc-populate M01 — expression-based, R4)

**Working oracle engine for `$populate`: `fhir.forms-lab.com` (fhirVersion 4.3.0 = R4B).**
The M00 spike had flagged the fhirpath-lab *R4* backend (`sqlonfhir 4.0.16`,
`sqlonfhir-r4.azurewebsites.net`) as **unable to evaluate `initialExpression`** and too fragile to
seed. Re-probed on 2026-07-12 against `fhir.forms-lab.com` — the R4B engine that already seeds the
`$extract` oracles — and it **does** evaluate `initialExpression` end-to-end:

| Field | Value |
|-------|-------|
| Engine | forms-lab (`fhir.forms-lab.com`) |
| FHIR version | 4.3.0 (R4B; wire-compatible with R4 for the primitives this case exercises) |
| Operation | `POST Questionnaire/$populate` (type-level) |
| Input shape | FHIR `Parameters`: `questionnaire` (resource) + `subject` (valueReference) + `context` (parts `name`=valueString, `content`=resource) |
| Captured | 2026-07-12, HTTP 200 |

**Case `populate-launchcontext-initial` (minimal, M01-scoped):** a Questionnaire carrying ONLY a
`launchContext` (`patient` → `Patient`) and two leaf items with `initialExpression`
(`%patient.name.first().given.first()` → `"Peter"`, `%patient.name.first().family` → `"Chalmers"`).
The form deliberately uses no mechanism M01 does not implement, so a subset implementation matches the
reference output. Fixtures:

- `Fixtures/Populate/populate-launchcontext-initial.questionnaire.json` — **input**, authored (input-side
  artifacts may be authored; only expected output must be vendored).
- `Fixtures/Populate/populate-launchcontext-initial.patient.json` — **input** launch-context Patient.
- `Fixtures/Populate/populate-launchcontext-initial.expected-qr.json` — **frozen forms-lab output**
  (the vendored oracle; never hand-authored, never seeded from this toolkit).

**Spec-legal divergences to tolerate in the test subclass (not the shared harness):** forms-lab omits
`QuestionnaireResponse.subject` from its output (subject is 0..1 optional). This toolkit sets `subject`
per the SDC populate guidance, so `FHIRPopulateConformanceTest` adds `subject` to its local
`IGNORED_KEYS` (mirroring how the extract subclass drops `url`) — dropping it in the shared base would
weaken the `$extract` oracle. `authored`/`id`/`lastUpdated`/`text` are already dropped by the shared base.

---

## `$populate` M02 mechanism probes (forms-lab, R4B) — 2026-07-13

Probed `POST https://fhir.forms-lab.com/Questionnaire/$populate` to establish which M02 mechanisms the
reference engine supports before building each (oracle-first). All returned HTTP 200.

- **`variable` (root)** — SUPPORTED. A root `http://hl7.org/fhir/StructureDefinition/variable` with
  `valueExpression {name:'pName', expression:'%patient.name.first()'}` is resolvable by a later item's
  `initialExpression` `%pName.given.first()` → `"Peter"`.
- **Type coercion** — SUPPORTED for the primitives the engine returns directly: `date` item ←
  `%patient.birthDate` → `valueDate`; `boolean` ← `%patient.active` → `valueBoolean`; `integer` ←
  `%patient.name.count()` → `valueInteger`. (Coding/Quantity/Reference adaptation still to be probed per
  case as those oracles are added.)
- **`itemPopulationContext` (repeating group)** — SUPPORTED and the key semantic: a group item with
  `itemPopulationContext {name:'nameCtx', expression:'%patient.name'}` is emitted **once per context
  result** (2 names → 2 `names` group items), and within each repetition `%nameCtx` is bound to *that*
  result element, so `%nameCtx.given.first()` / `%nameCtx.family` populate per-repetition.
- **`enableWhen` — NOT suppressed (spec-confirmed, not just reference-matched).** A `dependent` item
  disabled by `enableWhen (trigger = true)` with `trigger` populated to `false` was **still populated**
  (`valueString "Peter"`). This is not merely forms-lab's choice — the **normative SDC $populate spec**
  (`HL7/sdc input/pagecontent/populate.xml`) states: *"When pre-populating a questionnaire, it makes
  sense to 'fill in' as much data as possible, even if it may not always be needed. However, such data
  should not be shown to the user until the given item is 'enabled'."* So `enableWhen` is a **display-time**
  concern; populate fills disabled items. The toolkit matches this — it does NOT pre-suppress
  `enableWhen`-disabled items (reverses the original M02 plan task; see M02.md). Optional opt-in
  suppression is a possible future backlog item, not default behaviour. The same spec section confirms
  the empty-set rule: *"An empty set SHALL be treated as 'not answered' rather than being converted to a
  boolean value of 'false'."* — validating M01's empty→info-issue (no answer) design.

Probe payloads: kept out of the repo (synthetic, reproducible from the descriptions above).

---

## `$populate` M02 oracle fixtures — per-file provenance

The six M02 `$populate` oracle cases below share this provenance (recorded per-file here so the audit
trail matches the `$extract` corpus, rather than living only in the prose above):

| Field | Value |
|-------|-------|
| Engine | forms-lab (`fhir.forms-lab.com`) — the same R4B-native engine that seeds the `$extract` oracles and the M01 `populate-launchcontext-initial` oracle |
| FHIR version | 4.3.0 (R4B; wire-compatible with R4 for the content these cases exercise) |
| Operation / Endpoint | `POST https://fhir.forms-lab.com/Questionnaire/$populate` (type-level) |
| Captured | 2026-07-13, HTTP 200 |
| Input fixtures (`.questionnaire.json` / `.patient.json` / `.observation.json`) | **authored** — input-side artifacts may be authored; only the expected output must be vendored |
| `*.expected-qr.json` | **frozen forms-lab output** — vendored verbatim, never hand-authored, never seeded from this toolkit |
| Probe request payloads | **not retained** in the repo (reproducible from the input fixtures + the `Parameters` input shape documented in the M01 oracle entry above) |

Per-case files and what each locks against regression:

| Case (`Fixtures/Populate/<case>.*`) | Input fixtures | Expected oracle | Mechanism frozen |
|---|---|---|---|
| `populate-variables-coercion` | `.questionnaire.json`, `.patient.json` | `.expected-qr.json` | root `variable` (`%pName`) reused by a later item expression; primitive coercion (date/boolean/integer) |
| `populate-itempopulationcontext` | `.questionnaire.json`, `.patient.json` | `.expected-qr.json` | repeating group: one group repetition per `itemPopulationContext` result, `%ctx` bound per repetition |
| `populate-enablewhen-notsuppressed` | `.questionnaire.json`, `.patient.json` | `.expected-qr.json` | the `enableWhen` non-suppression reversal — a disabled dependent item is still populated |
| `populate-coercion-quantity` | `.questionnaire.json`, `.patient.json`, `.observation.json` | `.expected-qr.json` | `Quantity` datatype pass-through → `valueQuantity`; **two** launch contexts (`patient` + `obs`) |
| `populate-coercion-reference` | `.questionnaire.json`, `.patient.json` | `.expected-qr.json` | `Reference` datatype pass-through → `valueReference` |
| `populate-coercion-coding-marital` | `.questionnaire.json`, `.patient.json` | `.expected-qr.json` | `Coding` datatype pass-through → `valueCoding` (`display` asserted directly in `testCodingCoercionPreservesDisplay`, since the harness ignores it) |

The frozen bytes carry the forms-lab serialization signature (a `meta.lastUpdated` with an explicit
`+00:00` offset and .NET-style fractional seconds, e.g. `2026-07-12T23:26:32.6618845+00:00`), consistent
with the genuine engine origin above rather than a toolkit-produced output.

---

## `$populate` M02 coercion + observation findings — 2026-07-13

**Answer-value coercion is strict-by-source-datatype (forms-lab, confirmed by probe).** The expression
must already resolve to the FHIR datatype the item type expects; the engine rejects a mismatch with a
fatal `OperationOutcome` (`invalidAnswerType`), it does not lenient-coerce:

- `choice`/`open-choice` ← a `Coding` datatype → `valueCoding` (system/code/display intact). A
  `CodeableConcept` is rejected — use `.coding.first()`. Oracle: `populate-coercion-coding-marital.*`.
- `quantity` ← a `Quantity` datatype → `valueQuantity` (value/unit/system/code intact). Oracle:
  `populate-coercion-quantity.*` (uses a **two-launchContext** input: `patient` + `obs`).
- `reference` ← a `Reference` datatype → `valueReference` verbatim. A bare string or whole resource is
  rejected. Oracle: `populate-coercion-reference.*`.

The toolkit matches this: complex-typed items pass the datatype **object** through (the answer choice
normalizer maps the object's class → the right `value[x]`); a bare scalar for a complex item is a
mismatch warning. **DEFERRED (→ `backlog.md`):** binding-driven `code`→`Coding` promotion — forms-lab
turns a bare `code` (e.g. `%patient.gender`) into a systematised `valueCoding` by reading the item's
required value-set binding (`gender` → `system: http://hl7.org/fhir/administrative-gender`). Replicating
that needs terminology-binding resolution the populate engine does not carry, so a bare code for a choice
item is currently a mismatch rather than a promoted Coding.

**Observation-based population (`observationLinkPeriod`) — NO reachable reference oracle.** Probed
forms-lab (`QForms`, publisher brianpos.com, fhirVersion 4.3.0 R4B) exhaustively: it does **not**
implement `observationLinkPeriod`. Its CapabilityStatement advertises no `Observation`/`Patient` resource
types and no store, so the spec-standard "server queries its own clinical record" path is structurally
impossible there; and when the candidate Observations were supplied as a `Bundle` launch context, the
`observationLinkPeriod` item still populated empty. A **control** in the same request (an
`initialExpression` reading the same Bundle) DID populate — proving the Bundle context is reachable and
the engine simply ignores `observationLinkPeriod` semantics (code-match + period + most-recent). A wide
explicit `valuePeriod` ruled out a date-window artifact. **Consequence:** observation-based `$populate`
is implemented and unit-tested **deterministically** (spec-driven: match `item.code` codings, filter
Observations to status in {final, amended, corrected} within the link period, restrict to the populate
subject when one is stated — see below, choose the most recent by effective time) — never oracle-seeded.
Same sanctioned exception SOURCES.md already records for observation-based `$extract`.

**Subject scoping (strict-exclude, decided 2026-07-16).** When `PopulateContext::$subject` is set, an
Observation must be confirmably about that subject to be eligible: `ObservationSelector` compares the
`Type/id` tail of `Observation.subject.reference` against the requested subject (tolerating an
absolute-URL prefix / `_history` suffix), and excludes any code+status+window candidate that is for a
different subject **or carries no readable subject**. This aligns with the `observationLinkPeriod` spec
intent (the server draws on the record *for that patient*) and guards the offline-first data seam against
a broad/mixed-subject `Bundle` leaking another patient's value. When candidates matched by code and
status but none could be subject-confirmed, the item is left unanswered with a **warning** (not the
softer "nothing matched" information issue). With no subject stated, selection stays code/status/window
only. Covered by `FHIRQuestionnaireObservationPopulateTest` (`testSubjectScopeExcludesOtherPatientObservation`,
`testAllCandidatesWrongSubjectLeavesUnansweredWithWarning`, `testSubjectAbsentObservationExcludedWhenSubjectEnforced`,
`testAbsoluteAndVersionedSubjectReferenceStillMatches`).

---

## `$populate` M03 — Conformance corpus finalisation (full SDC-IG triage)

Authoritative upstream list captured `2026-07-13` from
`gh api repos/HL7/sdc/contents/input/resources?ref=master` (every `Questionnaire`/`StructureMap`
example that could exercise `$populate`). Every populate-relevant SDC-IG example is triaged below —
covered by a vendored oracle, or deferred with a reason and a `backlog.md` pointer. No SDC-IG example
is silently unaccounted for.

| SDC-IG example (`HL7/sdc` `input/resources/`) | Populate mechanism(s) | Status |
|---|---|---|
| `Questionnaire-CardiologyForm.json` | `calculatedExpression` only | **Deferred** — `calculatedExpression` (continuous re-population as source answers change) needs a re-evaluation trigger model. Carries no `launchContext`/`initialExpression`, so it is not a plain-`$populate` oracle candidate. → `backlog.md` (Next). |
| `Questionnaire-rxterms.json` | `x-fhir-query`, `answerExpression`, `calculatedExpression`, `variable` | **Deferred** — dominated by `x-fhir-query` (live `dataEndpoint` fetch) and `answerExpression` (interactive answer-option selection); both are out of scope for the offline-first, headless engine. → `backlog.md` (`x-fhir-query`/`dataEndpoint` under Later; answer-selection under Maybe, `candidateExpression`/`contextExpression`). |
| `Questionnaire-trivia-questionnaire.xml` | none | **Not applicable** — a quiz form carrying no populate directive (no `initialExpression`/`launchContext`/`itemPopulationContext`/`variable`); nothing for `$populate` to exercise. |
| `StructureMap-questionnaire-population-transform.xml` | StructureMap-based population (`sourceStructureMap`) | **Deferred** — requires a FHIR Mapping Language engine, absent from the toolkit. Shared with StructureMap-based `$extract`; largest deferred item. → `backlog.md` (Maybe, "StructureMap-based population"). |

**Corpus-coverage bias (the honest consequence — recorded, not a gap to close).** Every published
SDC-IG populate example above uses a mechanism this engine deliberately does not implement
(`x-fhir-query`, `calculatedExpression`, `answerExpression`, StructureMap). None can serve as an
oracle case for the offline-first / FHIRPath-only feature set. The mechanisms the toolkit **does**
implement — `launchContext` + `initialExpression`, root/item `variable`, `itemPopulationContext`
repeating groups, and datatype coercion — are therefore proven against **authored-input +
forms-lab-vendored-output** oracles (the seven `populate-*` cases documented above), not against IG
example forms. This skew toward simpler forms is the expected outcome of the offline-first boundary and
is recorded as decision item (a) in the sdc-populate ADR (`ADR-011`).

**Covered mechanism corpus (vendored forms-lab oracles, not IG examples):**
`populate-launchcontext-initial` (M01), plus the six M02 cases (`populate-variables-coercion`,
`populate-itempopulationcontext`, `populate-enablewhen-notsuppressed`, `populate-coercion-quantity`,
`populate-coercion-reference`, `populate-coercion-coding-marital`) — see the per-file provenance table
above. Observation-based population is proven deterministically (no reachable oracle), as recorded in
the observation-based section above.

**phpdoc / gruff-php `docs.*` decision (M03).** The public API surface of the populate files was given
full phpdoc (`populate()`, all `__construct()`s with `@param` tags, `PopulateResult`,
`BundlePopulationDataProvider`; `docs.missing-public-phpdoc` and `docs.missing-property-phpdoc` → 0). The
residual `docs.missing-param-tag` / `docs.missing-return-tag` / `docs.return-comment` findings on
**private** helpers are **accepted advisory debt, not filled with type-only tags**: `code-comments.md`
forbids restating a type signature, and the already-shipped `$extract` files
(`FHIRQuestionnaireResponseExtractService`, `TemplateExtractor`) carry the identical density unaddressed
with no repo-wide baseline — so filling only the populate files would be an inconsistency, not an
improvement. No `.gruff-baseline.json` was introduced (the repo uses none). Decision made in M03 with
explicit maintainer approval.
