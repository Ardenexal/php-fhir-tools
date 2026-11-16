---
category: valueset-enum-case-naming
last_reviewed: 2026-06-19
---

# Footguns: ValueSet → Enum Case Naming

## Footgun: the inline-concept path silently dropped/crashed on symbol & collision codes

**Status:** active | **Created:** 2026-06-19 | **Evidence:** OBSERVED (CDA M3)

`FHIRValueSetGenerator` has two code paths that turn ValueSet concepts into enum cases, and until
M3 they did NOT have the same defensive guards:

- **CodeSystem-resolved path** (`addConceptsFromCodeSystem` first block): names via the slugger
  (`getEnumName`, which maps `<`,`>`,`=`,`&` to words), guards empty names (throws), guards
  numeric-leading (`CODE_` prefix), dedups.
- **Inline-concept path** (second block, `compose.include[].concept`): named via raw
  `u($display)->upper()->snake()` — **no symbol mapping, no empty guard, no numeric guard** — and
  dedups by *silently* skipping a colliding case name.

Consequence on CDA `hl7.cda.uv.core`: `CDAObservationInterpretation` includes the symbolic codes
`<` and `>`. `snake()` strips them to the empty string `''`, which Nette rejects with
`Value '' is not valid name`, aborting that whole enum. The silent-dedup is the more dangerous
sibling: two codes that slug to the same name would drop one — and for a **required** binding that
makes a legal coded value unrepresentable, with no error.

**Fix (M3):** in the inline-concept path, fall back to the slugger (`getEnumName`) + numeric guard
only when `snake()` yields an empty/numeric-leading name, then skip-if-still-empty rather than
abort the enum. The fallback fires only on the broken cases, so existing FHIR R4/R4B/R5 enum case
names are unchanged (verified: zero `Models/` churn after regenerating all three).

**Rule when generating enums from a new IG (e.g. M4 AU CDA):** do not trust that all 26-or-N
enums are complete because they `php -l` clean. Run the discriminating check —
**enum case count == number of DISTINCT codes across all `compose.include[].concept` (plus any
bundled CodeSystem concepts)** — for every ValueSet. `php -l` and a few spot-checks will not catch
a silently dropped collision; only the per-ValueSet count comparison will. See
[[structure-definition-sampling]] (same "don't extrapolate from a handful of samples" root).
