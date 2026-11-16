---
category: structure-definition-sampling
last_reviewed: 2026-06-19
---

# Lessons: StructureDefinition Structure Assumptions

## Lesson: Do not extrapolate package-wide SD structure from a handful of samples

**Status:** active | **Created:** 2026-06-19 | **Evidence:** OBSERVED

During the CDA M2 spike, a 5-SD sample (`ANY`, `II`, `CD`, `InfrastructureRoot`,
`ClinicalDocument`) all had `baseDefinition = .../ANY`, which suggested — and was briefly
recorded in the milestone as a determined fact — that the CDA core hierarchy is **flat**
("everything extends ANY, do not synthesise an intermediate hierarchy").

A full scan of all 124 generatable SDs in `hl7.cda.uv.core#2.0.2-sd` overturned this: the
`baseDefinition` distribution is `InfrastructureRoot`=81, `ANY`=13, `QTY`=6, `SXCM-TS`=4,
`ST`=3, `Base`=3, `CV`=3, `EN`=3, … The act/role/entity classes (`Act`, `Observation`,
`SubstanceAdministration`, `Section`, …) all extend `InfrastructureRoot`, and datatypes form
multi-level chains (`IVL_PQ → … → QTY → ANY`; `CE → CV`). The hierarchy is **multi-level**, so
a generator MUST do parents-first topological ordering with cycle detection — a single-level
generator (which the "flat" finding would have invited) breaks the moment a chain exists. The
flat sample also happened to contradict the prior deep-research finding (ANY's rendered children
were datatypes + `InfrastructureRoot`, not the act classes).

Two related sampling traps surfaced in the same spike:

- **`type != url` is not always a specialization signal.** 12/124 core SDs have `type != url`,
  but they are hyphen/underscore separator mismatches (`url=.../IVL-TS`, `type=.../IVL_TS`) —
  the same type, not a parent. The `type`-points-at-another-class case is cross-package (AU →
  core), not intra-package.
- **A "present on every SD" extension may have exactly one exception.** The `xml-namespace`
  tooling extension is on 123/124 SDs — `AlternateIdentification` lacks it. Deleting the
  parent-chain fallback "because it's always direct" would yield `xmlNamespace=null` and wrongly
  permit JSON serialization for that CDA class.

**Rule:** Before encoding a structural claim about a generated package (inheritance shape,
extension presence, field invariants) as a determined fact in a plan or generator, run the
discriminating query across **all** relevant SDs and count distinct values — never extrapolate
from a small sample. Keep defensive fallbacks (parent-chain walks, default branches) unless the
full-set scan proves they are unreachable.
