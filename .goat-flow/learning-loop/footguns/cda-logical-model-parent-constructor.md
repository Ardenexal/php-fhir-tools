---
category: cda-logical-model-parent-constructor
last_reviewed: 2026-06-21
---

# Footguns: CDA Logical-Model Generation

## Footgun: Subclass constructors must forward to `parent::__construct()` or inherited promoted properties throw

**Status:** active | **Created:** 2026-06-21 | **Evidence:** OBSERVED (CDA M4) | **hallucination-risk:** low

The CDA logical-model generator (`LogicalModelGenerator::generate`) emits each class with its own
properties as **constructor-promoted parameters** and *skips* inherited elements (they live on the
parent via `extends`). The trap: PHP does **not** run a parent constructor automatically. If a child
defines its own `__construct` and never calls `parent::__construct(...)`, the parent's promoted
properties are **declared but never initialised** — and a typed promoted property with a parameter
default (`public ?Foo $x = null`) does NOT get that default at the property level; the default is
only assigned when the constructor runs. Reading any inherited property then throws
`Error: Typed property ... must not be accessed before initialization`, even for nullable props.

This was latent across **all** core CDA subclasses since M2 (0 classes called `parent::__construct`)
and surfaced hard in M4 because AU classes (`AuClinicalDocument extends ClinicalDocument`) inherit
property-rich core parents.

**Fix (implemented M4):** each class re-declares its parent's full ordered parameter list as
**non-promoted** params (no `FhirProperty` attribute — that metadata stays on the parent's promoted
property) and forwards them via `parent::__construct(<named args>)`. The parent's full list is
memoised through the `parentOf` chain (`fullParams(url) = ownParams(url) ++ fullParams(parentOf)`),
which is order-independent — do NOT rely on the `baseDefinition` topological sort, because `parentOf`
is type-aware (`au-ClinicalDocument` and `ClinicalDocument` both have `baseDefinition=ANY`, so the
sort does not place the type-parent first). Named args keep the call order-independent.

**Verification that actually proves it:** instantiate a deep subclass and read an inherited property
— `new II()` (core, extends `ANY`) → `nullFlavor` is `null`; `new AuClinicalDocument()` →
inherited `classCode` is `'DOCCLIN'`. "Looks correct" / PHPStan-clean does NOT catch this; only a
runtime instantiation + property read does. See [[valueset-enum-case-naming]] for the sibling AU enum trap.


## Footgun: an AU profile snapshot is NOT a superset of its parent's elements, and reflection order is not content-model order

**Status:** active | **Created:** 2026-08-31 | **Evidence:** ACTUAL_MEASURED (issue 115) | **hallucination-risk:** high

Two traps that look like one. Both were live in shipped CDA output: `templateId` serialized last on
every AU act, and `completionCode` last on `AuClinicalDocument`.

**Trap 1 — reflection order.** `ReflectionClass::getProperties()` returns a class's OWN properties
first and its ancestors' last. The CDA generator declares inherited elements on the parent and
forwards them (see the parent-constructor footgun above), so `InfrastructureRoot`'s `realmCode`,
`typeId` and `templateId` — which the content model places FIRST — reflect LAST on every class that
extends it. `ClinicalDocument` is the one exception and the reason this went unnoticed for so long:
it extends `ANY`, not `InfrastructureRoot`, so it declares those three itself in snapshot order and
the document root validates while every act inside it does not. The class hierarchy cannot fix this
either: an AU child's own element can belong in the MIDDLE of its parent's sequence
(`completionCode` sits between `versionNumber` and `copyTime` on `ClinicalDocument`), so neither
own-first nor ancestors-first ordering is correct.

**Trap 2 — the snapshot superset assumption.** `cdaDirectPropertyNames`' docblock says "the snapshot
inlines inherited elements, so a type's set is a superset of its parent's". That is FALSE for AU
profiles: an AU snapshot omits the `sdtc` extension elements its core parent declares. Measured over
all 247 CDA classes, deriving each class's ordered element list from its own snapshot alone covered
only 201 — `au-SubstanceAdministration` loses `sdtcInFulfillmentOf1`, `au-ClinicalDocument` loses
`sdtcCategory` and `sdtcStatusCode`, `au-Patient` loses seven. Anchor on the parent's resolved list
and splice the child's own elements in at their snapshot-implied positions; measured zero
child-versus-parent order contradictions across all 247, which is what makes the merge safe.

**Evidence:** `src/Component/CodeGeneration/src/Generator/ContentModelOrderResolver.php`
(search: "So the parent's resolved list is the anchor") — the merge, with the measurements in its
docblock. `src/Component/Serialization/src/Normalizer/Xml/FHIRComplexTypeXmlNormalizer.php`
(search: "orderByContentModel") — emission orders the property list, not the finished array, so every
emit branch lands correctly at once.

**A second, independent cause of the same symptom:** an element in a non-default XML namespace
(`sdtc`, AU/ADHA extensions) takes the buffered path and is folded in AFTER the emit loop
(search: "Buffered rather than written straight in"), so it lands last regardless of position. The
buffer exists because two properties can emit under one local name (`raceCode` vs `sdtcRaceCode` on
`Patient`) and a keyed write would drop one. Fix by reserving the position with a null placeholder the
fold writes back in place — never by removing the buffering.

**Prevention:** never infer CDA element order from reflection, the class hierarchy, or a child's own
snapshot. Order is recorded on the generated class as `#[LogicalModel(propertyOrder: [...])]`; read
that. Name lookups stay case-sensitive — `Section` declares both an `ID` attribute and an `id`
element. And note the shape of the test that matters: populate the elements that FOLLOW the one under
test, or "published position" and "appended last" are the same answer and the test passes against the
bug. The originally reported sample document had exactly that flaw.

