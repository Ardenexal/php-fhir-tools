# ADR-006: Temporal Range Comparison Strategy for Mixed-Precision Dates

**Status:** accepted
**Date:** 2026-05-28
**Milestone:** M13

## Context

FHIR `date` and `dateTime` values may be partial: `YYYY`, `YYYY-MM`, or `YYYY-MM-DD`. When
`FHIRTemporalRangeValidator` compares a partial element value against a `minValue[x]` /
`maxValue[x]` bound, the comparison semantics are ambiguous.

Example: element value `2023` (year-only) vs `maxValueDate: '2023-06-01'`.
- Conservative expansion: `2023` → max-bound `2023-12-31` → fails (2023-12-31 > 2023-06-01 → violation)
- Same-precision truncation: compare year only (both 2023) → passes (no violation)

PHP's `DateTimeImmutable::createFromFormat('Y', '2023')` fills today's month/day, not boundary
dates. Manual padding is required for all partial-date expansions.

## Options Considered

### Option 1 — Conservative bound expansion
Expand partial dates to the boundary of their precision before comparison:
- `YYYY` → min-side: `YYYY-01-01`; max-side: `YYYY-12-31`
- `YYYY-MM` → min-side: `YYYY-MM-01`; max-side: last day of month
- Full date: exact comparison

### Option 2 — Same-precision truncation
Truncate the bound to match the precision of the value, then compare:
- `2023` vs bound `2023-06-01` → compare `2023` vs `2023` → no violation

## Decision

**Option 1 — Conservative bound expansion.**

## Rationale

- Conservative expansion is safe for the common case: a partial date within a broad bound
  satisfies the constraint correctly.
- Same-precision truncation can silently miss true violations (e.g. value `2023` against
  `maxValue: 2022-12-31` — truncating to year, 2023 > 2022 correctly fires; but for
  `2023` against `maxValue: 2022-06-15` with truncation the result is correct, however the
  semantics become complex when the bound and value share the same year but differ in month).
- FHIR profiles that specify tight date bounds typically provide full dates. False positives
  from conservative expansion are acceptable; false negatives from missed violations are not.
- Fallback path: if conservative expansion causes false positives in integration testing,
  the pivot is to emit a `fhir:warning` (not error) for mixed-precision comparisons rather
  than a full skip.

## Consequences

- `FHIRTemporalRangeValidator` must manually pad partial dates to their boundary (min-side `YYYY-01-01`, max-side `YYYY-12-31`) before comparison — PHP's `DateTimeImmutable::createFromFormat('Y', '2023')` fills today's values, not boundary dates.
- If conservative expansion causes false positives in integration testing, the fallback is emitting `fhir:warning` instead of an error for mixed-precision comparisons.
