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
