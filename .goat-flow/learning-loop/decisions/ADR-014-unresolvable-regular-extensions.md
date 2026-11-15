# ADR-014: Unresolvable regular extensions are not errors

**Date:** 2026-08-31
**Status:** Accepted

## Context

The HL7 reference validator reports `The extension <url> could not be found so is not allowed here`
whenever it cannot locate an extension's definition. It is the single most common wording in the
shared corpus — 101 occurrences across the whole outcome set, and 31 of them (R4 30, R5 1) sit in
cases this project compares.

This project has always done something different: `FHIRValidationService` enforces only *modifier*
extensions, on the reasoning that a modifier changes the meaning of the containing resource in a way
that cannot be ignored, while an unrecognised regular extension is ignorable. That behaviour is pinned
by `FHIRModifierExtensionValidationTest::testUnknownRegularExtensionProducesNoViolation`.

M07 was tasked with closing the gap. The rule was built and measured against a clean-tree baseline:

- It closes **29 R4 findings**, including all 17 in `q-bp` and all 7 in `target-ref-profile-empty`.
- It closes **nothing on R5**, where it only over-reports.
- After a manifest-driven comparability gate removed 11 of 14 false positives, **four cases remain
  `ABOVE`** — we report where the reference validator does not.

Those four cannot be gated away, because the reference validator is inconsistent in a way no declared
input explains. It reports nothing at all — not even a warning — on
`http://example.org/additional-information` (`questionnaire-enableWhen-dw`),
`http://acme.com/some_url` (R5 `list-extension`) and `http://example.org/test` (R5 `q-bundle`), while
reporting 17 errors of exactly that shape on `q-bp`. `q-bp` and `questionnaire-enableWhen-dw` have
identical manifest key sets. Matching the reference validator here would mean reproducing an
undocumented inconsistency.

The plan's governing constraint is that no change may cause this validator to report a finding the
reference validator does not.

## Decision

**An unresolvable regular extension is not reported.** Only modifier extensions are enforced.

The 31 findings are recorded as a declared limitation —
`DeclaredLimitations::REASON_UNKNOWN_EXTENSION` — matched by the full sentence
`could not be found so is not allowed here` and pinned by `EXPECTED_FINDING_COUNTS` (R4 30, R5 1), so
the write-off cannot grow quietly.

Unlike the licence-bound limitations beside it, this one is a decision rather than an obstacle, and it
can be revisited. `DeclaredLimitationsTest::testEveryReferenceErrorHasADeclaredObstacle` was widened
from terminology-only to "any reason `DeclaredLimitations::reasonFor()` can name" to admit it, so the
decision is pinned rather than hidden.

## Consequences

- A caller gets a clean result on a resource carrying extensions whose definitions this project does
  not hold. Given that only core plus `hl7.fhir.uv.extensions` are generated, that is common.
- 29 R4 findings stay in the declared column rather than the open one. `q-bp` and
  `target-ref-profile-empty` will never reach `EQUAL` while this stands.
- Structural extension rules are unaffected and **were** implemented in M07: missing `Extension.url`,
  non-absolute `Extension.url`, and a value whose type the definition does not allow. Those need no
  definition lookup, so the decision does not reach them.
- The rejected implementation is kept at
  `.goat-flow/plans/validation-corpus-parity/M07-rejected-unknown-extension-rule.patch` so revisiting
  starts from measured code rather than a rebuild.

## Alternatives considered

- **Match the reference validator.** Rejected: leaves four cases `ABOVE`, which is the one direction
  the comparison must never move, and no gate can remove them.
- **Report only for HL7-canonical URL namespaces.** Would close `q-bp`'s SDC findings but not
  `target-ref-profile-empty`'s `fkcfhir.org` ones, and its `ABOVE` effect was never measured. A
  heuristic fitted to the corpus rather than a rule, so not adopted.
- **Report as a warning instead of an error.** Not evaluated in M07. It would leave the error
  comparison unchanged while surfacing the information, and is the most likely shape if this is
  revisited — but warning parity already differs by design on 97 cases (ADR-004), so it needs its own
  measurement first.

See also [ADR-004](ADR-004-extensible-preferred-binding-strictness.md), the precedent for a human
ruling that a class of finding stays non-error, and
[footguns/conformance-harness-wiring.md](../footguns/conformance-harness-wiring.md).
