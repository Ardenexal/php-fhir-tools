---
category: serialization-element-order
last_reviewed: 2026-09-02
---

## Footgun: FHIR XML element order rests entirely on reflection enumeration order, and no fixture guards it

**Status:** active | **Created:** 2026-09-02 | **Evidence:** ACTUAL_MEASURED
**Decision changed:** Any change to how the XML normalizers enumerate properties must be proven against
element order directly. A green suite is not evidence that order survived.
**Trigger phase:** ACT
**hallucination-risk:** high

**Symptoms:** Serialized FHIR XML emits child elements in the wrong sequence -- `<given>` before
`<family>` in a `HumanName`, for instance -- producing documents that are invalid against the FHIR
schema. Nothing fails. JSON output is unaffected, so a round-trip through the library also succeeds
and hides it.

**Why it happens:** Two mechanisms have to agree, and only one of them exists for FHIR.
`FHIRComplexTypeXmlNormalizer::normalizeForXML` writes into the output array in property-iteration
order, so whatever sequence the enumeration yields becomes the emitted element order. The sort meant
to correct that, `orderByContentModel`, returns its input untouched when the published order is
empty -- and `contentModelOrder()` is empty for everything outside CDA, because only CDA logical
models carry a `propertyOrder` on their `#[LogicalModel]` attribute. So for every FHIR resource and
complex type in the library there is no content model at all, and reflection order is the only thing
deciding the document.

That would be tolerable if a fixture pinned it. Until 2026-09-02 none did. Reversing the enumeration
input left all 4678 tests green while emitting schema-invalid documents, confirmed by diffing one
serialized Patient before and after. CDA has real coverage -- `CdaElementOrderTest` catches a
neutered sort -- but CDA is the only part that does, and even there the coverage does not extend to a
reordered input.

**Evidence:**
- `src/Component/Serialization/src/Normalizer/Xml/FHIRComplexTypeXmlNormalizer.php`
  (search: `private static function orderByContentModel`) -- returns `$properties` unchanged when
  `$order === []`.
- `src/Component/Serialization/src/Normalizer/Xml/FHIRComplexTypeXmlNormalizer.php`
  (search: `protected function contentModelOrder`) -- the base implementation returns `[]`, and the
  only override is `FHIRLogicalModelXmlNormalizer::contentModelOrder`, which reads a CDA-only
  attribute field.
- `src/Component/Serialization/tests/Integration/OutputSnapshotBaselineTest.php`
  (search: `testFhirXmlElementOrderFollowsThePropertyEnumerationSequence`) -- the guard added for
  this, verified as the single failure under a reversed enumeration.

**Prevention:** Treat the property enumeration in the XML normalizers as the FHIR content model,
because it is. Before changing it -- swapping the enumeration source, porting the ordering comparator,
introducing a cache -- reverse the enumeration input deliberately and confirm a test fails. If none
does, the change is unguarded regardless of how green the suite looks.

Two traps specific to proving it. `testCdaLogicalModelEmitsInPublishedContentModelOrder` looks like
the ordering guard and is not: `ClinicalDocument` declares `id` before `code` in reflection order as
well as published order, so that assertion holds with the sort deleted entirely. And the ordering
tests live in `src/Component/Serialization/tests/Integration`, which the `unit` suite does not scan --
`composer test-ai-unit --filter OutputSnapshotBaselineTest` reports OK on 2316 tests without
collecting one of them. Use `composer test-ai`.
