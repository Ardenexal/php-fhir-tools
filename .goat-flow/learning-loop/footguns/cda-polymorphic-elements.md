---
category: cda-polymorphic-elements
last_reviewed: 2026-09-09
---

# Footguns: CDA Polymorphic Elements

## Footgun: a partial-order sort that stops at the first non-ancestor leaves subtypes behind supertypes

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED (issue 117) | **hallucination-risk:** high

Variant lists for a polymorphic element must be ordered subclass-before-superclass, because matching
returns the first `instanceof` hit. Sorting them is not an ordinary sort: the relation is a **partial**
order, and most members of a real slot are unrelated siblings.

The trap is writing the insertion scan as "walk left while the candidate is a descendant, break
otherwise". That is correct only when related pairs are adjacent. Given `[CD, ST, CE]` — `ST` unrelated
to both — the scan hits `ST`, breaks, and `CE` never overtakes `CD`. An observation's value admits 29
datatypes of which only 17 pairs are related, so the gap is the normal case, not an edge case.

**Fix:** scan the whole left side and remember the LEFTMOST ancestor found; never break early.
`CdaTypeHierarchy::sortDescendantFirst()`
(`src/Component/CodeGeneration/src/Generator/CdaTypeHierarchy.php`, search: "LEFTMOST ancestor").

**Verification that actually proves it:** a fixture with an unrelated type BETWEEN a related pair
(`CdaTypeHierarchyTest::testASubtypeIsFoundPastAnUnrelatedTypeSittingBetween`). A fixture of only
related types passes against the bug. Confirm on real output by reflecting over every generated class
and testing each emitted variant pair with `is_subclass_of` — 8 polymorphic properties, 0 violations.

**Note the definitions are ordered the WRONG way:** `Observation.value` lists `CD` before `CE`/`CO`/`CS`/`CV`,
`TS` before its four interval types, and `INT` before `INT_POS` — 17 violations in one element. Never
emit published order as-is. See [[choice-variant-ordering]] for the same trap in FHIR `value[x]`.

## Footgun: `phpType` on `#[FhirProperty]` means the ARRAY ITEM type, so setting it on a single-valued property builds a list

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED (issue 117) | **hallucination-risk:** high

`PropertyMetadata::$phpItemClass` comes from `FhirProperty::$phpType`, and the XML denormalizer's array
branch is gated on `phpItemClass !== null` — **not** on `isArray`. So a non-repeating property that
declares `phpType` takes the array branch, and deserialization fails with
`Cannot assign array to property ... of type ?X`.

The plain generator path only sets it under `if ($isArray && $itemFqcn !== null)`, which is why this
never surfaced before: no single-valued property had ever declared it. Two of the nine CDA polymorphic
elements are single-valued (`Criterion.value`, `ObservationRange.value`), so a new emitter hits it
immediately.

**Prevention:** set `phpType` only when the property repeats. Any new `propertyKind` must be exercised in
both arities — a repeating-only test passes against this bug.

## Footgun: an element's text content is dropped when it arrives inside a repeating complex property

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED (issue 117) | **hallucination-risk:** high

`AbstractFHIRNormalizer::stripXmlMetaKeys()` (search: "str_starts_with($key, '#')") drops every
`#`-prefixed key recursively, and `#` is exactly where Symfony's `XmlEncoder` puts an element's text
content. It runs from `unwrapXmlValue($value, 'array')`, so it applies to every repeating complex
property.

Result: `<value xsi:type="ST">No Recommendations.</value>` inside a repeating slot reads back with every
attribute correct and an **empty** text value. Attributes surviving is what makes this hard to spot — the
object looks populated.

Latent until a repeating complex property could hold a text-bearing datatype. A scan across all 248
generated CDA classes found none before the polymorphic widening, which is why no test caught it. The
comment and CDATA markers SHOULD still be stripped; only `#` itself must survive.

**Verification that actually proves it:** assert the text content, not the class and not the attributes.
Deserializing the same datatype as its own document root works, so a root-level control test passes
against the bug — compare nested against root.

## Footgun: a CDA attribute literally named `value` never deserializes

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED (issue 117) | **hallucination-risk:** high

The normalizer treats `@value` as a FHIR primitive's content throughout (`<status value="active"/>`):
`unwrapXmlValue()` collapses an array whose only keys are `@value`/`#`, and the single-valued branch
unwraps `$denormalizedValue['@value']` again. CDA reuses `value` as an ordinary XML attribute on
**nine** datatypes — `PQ`, `IVL_INT`, `TEL`, `PQR`, `MO`, `BL`, `TS`, `REAL`, `INT` — and it is silently
swallowed on all of them.

Reproduce without any polymorphism: deserialize `<PQ xmlns="urn:hl7-org:v3" value="5" unit="mg"/>` as
`PQ`. `unit` is `'mg'`; `value` is `null`.

**Prevention:** do not assert on a CDA `value` attribute when testing something else — the failure looks
like the feature under test. Fixing it means changing `@value` handling shared with FHIR and needs FHIR
regression cover; tracked in `.goat-flow/plans/cda-xsi-type/backlog.md`.
