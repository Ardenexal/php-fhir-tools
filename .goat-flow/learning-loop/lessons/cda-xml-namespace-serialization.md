---
category: cda-xml-namespace-serialization
last_reviewed: 2026-09-10
---

# Lessons: CDA Logical-Model XML Serialization

**Context:** Wiring CDA `#[LogicalModel]` classes through the Symfony-serializer-based pipeline.

## Lesson: Reach a new structure kind by reflection rather than by widening the metadata-extractor interface

**Status:** active | **Created:** 2026-08-20 | **Evidence:** OBSERVED
**Decision changed:** A new structure kind gets a dedicated normalizer that detects its attribute through a shared locator trait; do not add predicates to `FHIRMetadataExtractorInterface` to make room for it.
**Trigger phase:** ACT

- **Route by reflection, not the metadata-extractor interface.** CDA classes carry only
  `#[LogicalModel]` (not `#[FhirResource]`/`#[FHIRComplexType]`), so `isComplexType`/`isResource`
  decline and a dedicated `FHIRLogicalModelXmlNormalizer`/`...JsonNormalizer` are the sole
  claimants. Detecting the attribute via a shared `LogicalModelLocatorTrait` keeps
  `FHIRMetadataExtractorInterface` (and its anonymous test mock) stable — adding interface methods
  would have forced mock churn for no benefit.
- **Reuse `FHIRComplexTypeXmlNormalizer::normalizeForXML`** (widened `private`→`protected`) instead
  of reimplementing the property loop. The new logic is just root `@xmlns` + per-element
  `xmlNamespace`.
- **Root-only namespace via a `__cda_nested` context flag.** Nested CDA datatypes recurse through
  the same normalizer; set the flag on the child context so only the outermost element declares
  `xmlns="urn:hl7-org:v3"`. Re-declaring on every child both bloats output and breaks byte-level
  round-trips.
- **`PropertyMetadata->xmlNamespace`** (plumbed from `#[FhirProperty]`) drives per-element
  namespaces; `sdtc*` property names are stripped to their bare local name at serialize time.

## Lesson: `@xmlns` redefines the default namespace for every descendant, so an extension element silently reparents the CDA content nested inside it

**Status:** resolved | **Created:** 2026-08-20 | **Resolved:** 2026-09-07 | **Evidence:** OBSERVED
**Decision changed:** Carry the in-scope default namespace on the serialization context and declare a namespace on a child only where it differs; when a change moves many namespace sites, measure the consequence against a published fixture instead of generalising from the one case that was settled.
**Trigger phase:** ACT

`@xmlns` is a **default-namespace redefinition**: it applies to the element AND every descendant
that doesn't redeclare. So children of a *populated* sdtc/AU extension element inherited the
extension namespace, and the wrapped type's own `#[LogicalModel]` namespace was never consulted.

**Resolved.** Extension *content* does stay in `urn:hl7-org:v3` — confirmed against real AU CDA
usage in issue #116, where the affected subtree carried a practice group's HPI-O. The fix is neither
a prefixed form nor unconditional redeclaration: `FHIRComplexTypeXmlNormalizer` now carries the
in-scope default namespace on the serialization context (`XML_DEFAULT_NAMESPACE_CONTEXT_KEY`) and
declares a namespace on a child element only where it differs from that scope. A prefixed form was
considered and rejected — same infoset, but it needs a prefix registry and a root binding collected
across the whole subtree.

**The metadata already distinguished the two cases**, which is why no regeneration was needed.
Content that genuinely belongs to the extension namespace carries a property-level `xmlNamespace` on
every child (`AuAsEntityIdentifier::$id`, `AuSubstitutionPermission::$code`); content that does not
carries none and falls back to the declaring type's namespace
(`AuEmployerOrganization::$id`/`$name`/`$asOrganizationPartOf`). A survey of
`src/Component/CdaModels/src` splits the surface cleanly: 241 classes declare `urn:hl7-org:v3` and 6
declare `urn:hl7-org:sdtc` at class level; 87 AU and 82 sdtc declarations are property-level.

**Consequence for sdtc, which the old note left open** — measured, not reasoned. The six class-level
sdtc types (`INT_POS`, `IdentifiedBy`, `InFulfillmentOf1`, `InFulfillmentOf1ActReference`,
`Precondition2`, `PreconditionBase`) come out unchanged, because their children match their own
namespace: `Act.sdtcPrecondition2` emits `<precondition2 xmlns="urn:hl7-org:sdtc"><allTrue …/>` with
`allTrue` inheriting sdtc and declaring nothing. The 82 property-level sdtc sites hold core CDA types
(`CE`, `CD`, `II`) whose class namespace is `urn:hl7-org:v3`, so their element children now emit as
CDA: `Patient.sdtcRaceCode` with a populated `originalText` gives
`<raceCode xmlns="urn:hl7-org:sdtc" code="SDTCRACE"><originalText xmlns="urn:hl7-org:v3">`.

Do not read that second result as spec-confirmed. The *mechanism* is observed and matches the AU case
the issue settled, but no published sdtc fixture was consulted and nothing in the repo populates one —
every pre-existing sdtc assertion in the suite covers an attribute-only element, which is precisely
why the 82-site change moved no test.

Namespaced xmlAttrs (`CD->sdtcValueSet`) are unaffected: an unprefixed attribute is in no namespace.

**Evidence:** `src/Component/Serialization/tests/Unit/CdaExtensionNamespaceScopeTest.php`
(search: `testCdaTypedChildrenOfAnExtensionElementStayInTheCdaNamespace`) — asserts through DOM
`namespaceURI`, because the defect is invisible to `local-name()` and to prefix comparison.
