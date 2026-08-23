---
date: 2026-06-03
status: accepted
---

# ADR-007: Questionnaire Validator Architecture

**Status:** accepted
**Date:** 2026-06-03
**Milestone:** M03 (spike) / M04 (implementation)

> **Numbering note:** M03/M04 originally reserved *ADR-006* for this decision. That number
> was taken by [ADR-006: Temporal Range Comparison Strategy](./ADR-006-temporal-range-comparison-strategy.md)
> before the questionnaire work landed, so this decision is recorded as ADR-007. The milestone
> files have been updated to point here.

## Context

Questionnaire validation checks a `QuestionnaireResponse` against the `Questionnaire` that
defines it (linkId matching, required items, repeats cardinality, answer-type conformance,
enableWhen). The M03 spike confirmed the generated R4/R4B/R5 models carry enough metadata to
drive these structural checks without dynamic StructureDefinition or terminology loading:

- `QuestionnaireItem.linkId` is a reliable string key; the item tree walks recursively to build
  a flat index (linkIds are spec-unique within a Questionnaire).
- `QuestionnaireResponseItemAnswer.$value` is a nullable union covering each FHIR answer type.
- `QuestionnaireItemOperatorType` carries all six comparison operators plus `exists`.
- R4B and R5 `QuestionnaireItem` are structurally equivalent to R4 for the core rules.

Unlike `FHIRValidationService::validate()`, which validates a single resource, questionnaire
validation is inherently a **two-resource operation** — it needs both the response and its
source definition.

## Options Considered

- **Option A — Standalone `FHIRQuestionnaireValidator`:** accepts `$questionnaire` and
  `$response`, returns a `FHIRValidationReport`, no coupling to `FHIRValidationService`.
- **Option B — Extend `FHIRValidationService`:** add a `validate()` overload taking an optional
  `?object $questionnaire`, keeping a single entry point.

## Decision

**Option A.** A standalone `FHIRQuestionnaireValidator implements FHIRQuestionnaireValidatorInterface`
in `src/Component/Validation/src/`, with:

- **Zero-dependency construction:** `new FHIRQuestionnaireValidator()` — no constructor arguments.
- **Signature:** `validate(object $questionnaire, object $response, bool $strictStatus = true): FHIRValidationReport`.
  `$strictStatus = false` skips the required-item check regardless of response status (drafts);
  when true it applies only to `completed`/`amended` responses.
- **Version handling:** accepts `object` and dispatches across R4/R4B/R5 by class; raw FHIR item
  **type code strings** drive type dispatch, never the generated `QuestionnaireItemType` enum —
  that enum only carries the three hierarchy-root codes (`group|display|question`), see footgun
  `generated-enum-hierarchy-gap`.
- **Result merging:** both services return `FHIRValidationReport`; consumers concatenate
  `violations` for unified coverage. Questionnaire violations carry `FHIRQuestionnaireConstraint::class`
  in `constraintClass` so they remain distinguishable after a merge.
- **Occurrence counting is sibling-scoped:** a non-repeating child of a repeating group
  legitimately appears once per group instance.

## Rationale

- Maintains clean service boundaries per [ADR-002](./ADR-002-validator-component-location.md):
  no coupling into `FHIRValidationService` internals.
- A two-resource operation does not fit the single-resource `validate()` contract; overloading it
  (Option B) would muddy that contract and the DI wiring.
- The standalone validator can be instantiated and used without the Symfony container.

## Consequences

- Consumers must call **both** services for full QuestionnaireResponse validation and merge the
  reports (documented in `src/Component/Validation/README.md`).
- The validator never resolves `QuestionnaireResponse.questionnaire` canonical URLs — the caller
  supplies the source `Questionnaire` (the library has no server/registry context).
- **Out of scope (backlog):** SDC extensions (`enableWhenExpression`, `answerExpression`,
  calculated expressions, regex constraints), R5 `answerConstraint`, and canonical-URL resolution.

## Addendum: enableWhen answer-lookup scoping

enableWhen conditions reference another question by `linkId`. The spec resolves the referenced
answer at the **nearest enclosing occurrence**; this validator looks the answer up
**response-globally** instead. The approximation is **exact whenever the referenced question
occurs once** in the response (the overwhelmingly common case) and only diverges when the same
`linkId` repeats across multiple parent instances with differing answers.

Comparison rules for the conditional operators:

- `exists` checks presence/absence of any non-null answer for the question.
- `=` / `!=` normalize both operands to a comparable scalar (numerics collapse to float; Coding to
  `system|code`; Quantity and Reference to identity strings). **Incomparable operands satisfy
  neither `=` nor `!=`** — an item is never enabled on an unknown comparison (see PR #83 review).
- `>` / `<` / `>=` / `<=` map operands to orderable scalars (float for numerics, string for
  date/time primitives); incomparable operand types yield `false`.

Full nearest-occurrence resolution is deferred; the response-global approximation is the
documented conformance baseline.

## Amendment (2026-06-04): answer-constraint rules brought into scope

The original **Consequences › Out of scope** list excluded answer-constraint rules from the
validator. That boundary is **reversed** (user-approved 2026-06-04), driven by the M12 conformance
suite surfacing 36 corpus cases left `markTestIncomplete`. The following rules are now **in scope**
and tracked by milestones **M13–M16** in `.goat-flow/tasks/validation-features/`:

- **In scope (M13–M16):** min/max value (`minValue`/`maxValue` extensions), `maxLength` (core) /
  `minLength` (extension), max/min occurs, decimal places (`maxDecimalPlaces`), `regex`,
  `answerOption` static membership (incl. `optionExclusive`), Attachment content-type/size,
  Reference target resource-type / URL, primitive `time`/`url` value format, and Quantity min/max
  with UCUM unit handling. SDC `enableWhenExpression` is in scope via the existing `FHIRPathService`
  (M16) — a coverage check, not a new engine.

These rules are read from the source Questionnaire item's constraint extensions/properties and
compared against the response answer values; no server, registry, or terminology context is needed.

**Still out of scope (at that time):** terminology binding / value-set membership (`answerValueSet`,
quantity `unitValueSet`, the async/coding cases) — brought into scope by the second amendment below;
`answerExpression` / `calculatedExpression`; R5 `answerConstraint`; and canonical-URL resolution.

The conformance-test skip message is updated by M13 to distinguish "not yet implemented (M13–M16)"
from "deferred (terminology / FHIRPath / canonical-URL)".

## Amendment (2026-06-09): terminology / value-set membership brought into scope

The **terminology binding** boundary from the 2026-06-04 amendment is now reversed
(user-approved, questionnaire-terminology-validation plan, M01–M02).

**Decision:** `FHIRQuestionnaireValidator` accepts an optional `?FHIRTerminologyClientInterface $terminologyClient = null`
as a third constructor parameter. When `null` (the default), all terminology checks are silently
skipped — **zero-dependency construction is preserved**. Existing callers are unaffected.

**In scope (M01–M02):**

- `FHIRTerminologyClientInterface::validateCoding(string $valueSetUrl, string $system, string $code): bool`
  added to the client contract (M01).
- `InMemoryFHIRTerminologyClient` ships in `src/` for offline and test use (M01).
- `choice` and `open-choice` items: each `Coding` answer validated against `answerValueSet` via
  `validateCoding()` (M02). For `open-choice`, a `StringPrimitive` answer is always accepted per
  the FHIR R4 SHOULD semantics.
- `string` items: `StringPrimitive` answer validated against `answerValueSet` via `validateCode()` (M02).
- `quantity` items: `Quantity` answer's `system`+`code` validated against the
  `questionnaire-unitValueSet` extension via `validateCoding()` (M02). A quantity with no coded
  unit when a `unitValueSet` is declared is reported as an error.

**Still out of scope:** `answerExpression` / `calculatedExpression`; R5 `answerConstraint`;
canonical-URL resolution; `display` validation for Coding answers.
