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
