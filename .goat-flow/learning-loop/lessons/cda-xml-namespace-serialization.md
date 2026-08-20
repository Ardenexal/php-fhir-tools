# Lesson — CDA logical-model XML serialization (M5)

**Context:** Wiring CDA `#[LogicalModel]` classes through the Symfony-serializer-based pipeline.

## What worked

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

## Trap to remember (open, deferred to M6)

`@xmlns` is a **default-namespace redefinition**: it applies to the element AND every descendant
that doesn't redeclare. So children of a *populated* sdtc/AU extension element currently inherit the
extension namespace. If CDA requires extension *content* to stay in `urn:hl7-org:v3`, the fix is a
prefixed form (`<sdtc:category>` + a root `xmlns:sdtc` binding), not `@xmlns`. Unresolvable without a
published CDA fixture — verify against M6 fixtures before trusting any populated-extension output.
Same question applies to namespaced xmlAttrs (`CD->sdtcValueSet`).
