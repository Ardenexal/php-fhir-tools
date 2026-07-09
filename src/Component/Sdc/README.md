# FHIR SDC

Structured Data Capture (SDC) operations for the FHIR Tools toolkit.

This component implements the SDC operations:

- **`Questionnaire/$populate`** — pre-fill a `QuestionnaireResponse` from launch context and
  expression-based population directives.
- **`QuestionnaireResponse/$extract`** — extract FHIR resources (definition-based and
  template-based) from a completed `QuestionnaireResponse`.

**Namespace:** `Ardenexal\FHIRTools\Component\Sdc\`

## Status

Foundation only. This package currently ships the shared prerequisites both operations depend on
(see `.goat-flow/plans/sdc-foundation/`):

- The conformance oracle harness (`tests/Integration/AbstractSdcConformanceTest.php`) — a reusable
  test base that compares the **deserialized model** field-by-field against a frozen reference
  baseline, with an explicit ignore-list for spec-legal serialization divergence.

The `$populate` and `$extract` operation logic is delivered by the `sdc-populate` and `sdc-extract`
feature plans.

## Conformance oracle

Golden expected outputs must be vendored from a recognized SDC reference implementation (never
hand-authored) and frozen as the seeded baseline — see `tests/SOURCES.md` for the reference impl,
its version, and known fidelity caveats.
