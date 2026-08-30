---
date: 2026-08-27
status: accepted
---

# ADR-010: CDA Narrative Is a Markup String, Not a Generated Type

**Status:** accepted
**Date:** 2026-08-27
**Milestone:** CDA M9 (section narrative)

## Context

A CDA section carries human-readable narrative in `Section.text`. CDA requires that element to hold
a **StrucDoc markup tree** as element content — paragraphs, lists, tables, `content` spans with
`styleCode` — not text and not an attribute value.

Three facts constrain how this library can represent it:

1. **`hl7.cda.uv.core#2.0.2-sd` publishes no StrucDoc StructureDefinition.** A scan of all 139 SDs
   in the package finds no `StrucDoc*` definition of any kind. The upstream schema for the narrative
   block lives in the CDA XSD distribution
   (`schema/extensions/SDTC/processable/coreschemas/NarrativeBlock.xsd` in `HL7/CDA-core-2.0`), which
   is not a FHIR artifact and not something the StructureDefinition → PHP pipeline consumes. So the
   generator has nothing to generate a type from.
2. **The SD marks the element explicitly.** `Section.text` declares type `xhtml` and
   `representation: ["cdaText"]`. `cdaText` occurs exactly once in the whole package, and it is the
   only `xhtml`-typed element there — so either signal identifies the narrative unambiguously.
3. **A narrative-block class lattice is unpleasant to use.** Constructing a table through generated
   `StrucDoc.Table` / `Tbody` / `Tr` / `Td` objects is verbose, and callers migrating onto these
   models overwhelmingly already hold their narrative as markup text produced by a template or a
   reporting layer.

Before this change the property was typed `?string` and emitted through the generic scalar path as
`<text value="&lt;table&gt;…"/>` — escaped, into an attribute. That is not valid CDA under any
representation choice, so the emit path had to change regardless of the modelling decision.

## Options Considered

- **Option A — Generate a StrucDoc type lattice.** Faithful and self-documenting, and would let
  static analysis catch a malformed narrative. But there is no FHIR-shaped source to generate from
  (see fact 1), so it would mean hand-authoring or XSD-importing a parallel type tree, plus a
  serializer path for it. It also imposes object construction on every caller.
- **Option B — Keep the property a plain markup string, parse it on emit.** The model stores what
  callers already hold; the serializer parses the string into element content on the way out and
  reassembles it on the way back.

## Decision

**Option B — the narrative stays a plain `?string`, and the serializer converts.**

- `Section::$text` remains `?string` and holds StrucDoc markup as text.
- On emit, `FHIRComplexTypeXmlNormalizer` parses the string into a `DOMDocumentFragment` injected
  under the `'#'` key, so the markup becomes real child elements. Fragment children are created
  without an explicit namespace so they inherit `urn:hl7-org:v3` from the document root, matching
  published CDA.
- On read, the markup is recovered from the **source DOM element**, not from the decoded array.
- The markup is **not validated** against the narrative-block schema, and CDA narrative is excluded
  from FHIR's `txt-1`/`txt-2` narrative checks.

**Storing a string does not mean emitting one.** The two decisions are independent: the string is a
modelling choice, and the escaped attribute was a bug.

## Rationale

- **Nothing to generate from** (decisive): fact 1 means Option A cannot reuse the existing pipeline
  at all, so it is a new subsystem rather than more of the same.
- **Matches how callers hold narrative:** markup arrives from templates and reporting layers as
  text; an object lattice would force every caller to parse it just to re-serialize it.
- **Read order is preserved only via the DOM.** Symfony's `XmlEncoder::decode` regroups same-named
  siblings and destroys their interleaving, so `<p/><table/><p/>` would come back as two `p`s and a
  `table` with the order lost. Reading the source element sidesteps that; it is the same technique
  the transparent choice-group work already relies on.
- **FHIR narrative rules would reject valid CDA.** StrucDoc has several top-level nodes where FHIR
  requires one wrapping `div`, and `paragraph`/`content`/`linkHtml` are not HTML 4.0 elements.
  Measured: a valid CDA narrative drew three errors from `NarrativeXhtmlChecker` — a spurious
  malformed-XHTML report from the extra top-level nodes, `txt-1` for `paragraph`, and `txt-2`
  claiming there was no content at all. Skipping logical models is the narrowest possible fix.
- **No metadata change needed.** Because `Section.text` is the only `xhtml`-typed element in the CDA
  package, the serializer keys on the existing `fhirType`, so no new `#[FhirProperty]` argument and
  no `ardenexal/fhir-metadata` release are required.

## Consequences

- Malformed markup, and plain text with no markup at all, both emit as text content rather than
  raising. A narrative is author-supplied and must never be able to break document serialization.
- Nothing checks that the narrative is *valid* StrucDoc. A caller can emit markup a receiving system
  rejects. Recorded in the plan backlog; revisit only if a real consumer rejects documents for
  narrative-structure reasons.
- Round-tripping normalises the markup string: the recovered value carries no namespace declarations
  (they are re-established on emit) and reflects the parser's serialization of the same tree, so it
  is equivalent rather than guaranteed byte-identical for arbitrary input.
- Comments and processing instructions inside a narrative are dropped on read.
- If a future logical-model package introduces a second `xhtml`-typed element that is *not* a
  narrative block, keying on `fhirType` stops being sufficient and the `cdaText` representation must
  be carried into property metadata instead — which would then need a metadata release.
