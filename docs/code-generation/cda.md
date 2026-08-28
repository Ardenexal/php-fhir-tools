# Generating CDA Logical Models

CDA R2 (Clinical Document Architecture) and its derivatives — including the Australian Digital
Health Agency schema used for MyHealthRecord — are published on the FHIR package registry as
FHIR **logical models**. This page describes what is generated, how the packages are structured,
and the output namespace layout.

---

## CDA Package Landscape

### Available Packages

| Package | Version | FHIR base | Registry |
|---|---|---|---|
| `hl7.cda.uv.core` | `2.0.2-sd` | 5.0.0 (R5) | `packages.fhir.org` |
| `au.digitalhealth.cda.schema` | `1.0.1` | 5.0.0 (R5) | Pinned tarball — see Package Sources |

### Package Sources

`au.digitalhealth.cda.schema` is published to no FHIR registry: `packages.fhir.org` and
`packages.simplifier.net` both 404 on it. `PackageLoader::KNOWN_PACKAGES` therefore pins its tarball
URL and sha256, so the ~465KB binary stays out of git while generation stays reproducible:

```
https://implementer.digitalhealth.gov.au/fhir/cda-au-schema/1.0.1/package.tgz
```

The version segment is part of the pin. `current/` resolves to whatever the publisher last released
and would reintroduce exactly the drift described below, so pin the version directory even when the
release notes point at `current/`.

**Pin the ADHA implementer release, never `build.fhir.org`.** The CI artifact at
`https://build.fhir.org/ig/AuDigitalHealth/cda-au-schema/package.tgz` is a continuous build whose
bytes drift under a fixed version number. When its hash moved off the pin, CDA regeneration stopped
working entirely — and silently: `fhir:generate` clears the output directory before the package
error surfaces, so the run deleted every `Au*` class and still exited 0. Recovery is
`git checkout -- src/Component/CdaModels`. Check `git status src/Component/CdaModels` after any
generation run naming a CDA package; the exit code will not tell you.

Both packages use the standard FHIR `.tgz` format and are downloaded by the same
`PackageLoader` used for FHIR R4/R4B/R5 packages.

### Package Dependency Chain

```
au.digitalhealth.cda.schema#1.0.1
  └── depends on: hl7.cda.uv.core#2.0.2-sd
```

Generate the core package before the AU extension package.

### Australian Extensions vs FHIR Profiles

FHIR profiles use `derivation: constraint` to **restrict** an existing type.
The AU CDA schema uses `derivation: specialization` to **add new XML elements** — the same
mechanism as the HL7 international core. AU classes (`au-ClinicalDocument`,
`au-SubstanceAdministration`, etc.) extend corresponding core classes with
Australian-specific XML elements. They are proper subclasses, not constrained views.

**`derivation` does not settle what the element is called on the wire.** All 230 AU logical SDs are
`derivation: specialization`, but 68 of the 105 AU-namespace SDs point `type` at another published
type — and for XML that is a refinement: `au-ClinicalDocument` refines core `ClinicalDocument`, and
CDA requires `<ClinicalDocument>` on the wire. `au-ClinicalDocument` is a StructureDefinition name,
which is a *type* identifier; no CDA schema, schematron or consumer accepts it as an element name.

The generator records the relationship rather than resolving it: `#[LogicalModel]` carries
`refines`, the canonical URL from the SD's own `type` field (null when the definition introduces a
type of its own), and `fhir-serialization` follows that chain to the type that named the element.
Do not try to infer this from the shape of `url` or `name` — upstream defeats both. `au-Place` is
published under the **core** HL7 URL (`http://hl7.org/cda/stds/core/StructureDefinition/au-Place`)
despite refining core `Place`, while AU's own `code` type derives from `CE` without refining it; a
URL-authority heuristic gets those two wrong in opposite directions.

---

## Structural Differences from FHIR R4/R5

### Every StructureDefinition is `kind: logical`

```
FHIR R4/R5          CDA
─────────────       ──────────────────────────
kind: resource      kind: logical
kind: complex-type  kind: logical
kind: primitive     kind: logical
derivation: spec.   derivation: specialization
```

There are no `resource`, `complex-type`, or `primitive-type` kinds anywhere in CDA packages.

### CDA Inheritance Hierarchy

```
http://hl7.org/fhir/StructureDefinition/Base      ← FHIR root
  └── ANY
       └── InfrastructureRoot
            ├── ClinicalDocument
            ├── Section
            ├── Act, SubstanceAdministration, Observation, Organizer …
            ├── AssignedAuthor, PatientRole, RecordTarget …
            └── (AU) au-ClinicalDocument → ClinicalDocument
                 au-SubstanceAdministration → SubstanceAdministration …
```

V3 data types have their own branch:

```
ANY
  ├── QTY → TS, INT, REAL, PQ, MO, RTO
  ├── ST  → ED
  ├── BIN
  ├── II
  └── CS  → CE → CD
```

### CDA-Specific Element Type Codes

CDA element types use fully-qualified CDA StructureDefinition URLs:

| CDA type code | Meaning |
|---|---|
| `.../cs-simple` | `classCode`, `typeCode`, `moodCode` (XML attributes) |
| `.../oid` | OID string values |
| `.../II` | Instance Identifier |
| `.../TS` | Point in Time |
| `.../IVL_TS` | Interval of Time |
| `.../CS` | Coded Simple Value |
| `.../CE` | Coded with Equivalents |
| `.../CD` | Concept Descriptor |
| `.../ST` | Character String |
| `.../EN` / `PN` / `ON` | Entity / Person / Organisation Name |
| `.../AD` | Postal Address |
| `.../TEL` | Telecom Address |

(All prefixed `http://hl7.org/cda/stds/core/StructureDefinition/`)

### XML Attribute Representation

CDA properties that are XML attributes (not child elements) carry `representation: ["xmlAttr"]`.
Examples: `classCode`, `typeCode`, `moodCode`, `nullFlavor`, and II sub-properties (`root`,
`extension`). The generator emits these as `FhirProperty` with an `@`-prefixed
`xmlSerializedName`, which the XML serialiser already reads correctly.

### XML-Only Serialization

CDA document instances are XML-only. Generated CDA classes carry a `#[LogicalModel]` attribute
with `xmlNamespace: 'urn:hl7-org:v3'`, which the XML serialiser uses to emit the correct
namespace declaration on the document root. JSON serialisation of CDA classes throws a
descriptive exception.

---

## Generated Output Structure

CDA output ships as a **separate Composer package `ardenexal/fhir-cda-models`** (a new monorepo
component), isolated from the `ardenexal/fhir-models` package that holds R4/R4B/R5 — see
[ADR-009](../../.goat-flow/learning-loop/decisions/ADR-009-cda-models-package-boundary.md):

```
src/Component/
├── Models/        ← ardenexal/fhir-models  (R4, R4B, R5)
│   └── src/{R4,R4B,R5}/
└── CdaModels/     ← ardenexal/fhir-cda-models  (CDA core + AU)
    └── src/
        ├── DataType/        ← V3 data types: II, TS, CS, CE, CD, ST, EN, AD, TEL, IVL_TS …
        │                       Base types: ANY (abstract), InfrastructureRoot
        ├── ClinicalClass/   ← CDA act/role/entity/participation classes: ClinicalDocument, Section …
        │                       AU extensions: AuClinicalDocument, AuSubstanceAdministration …
        └── Enum/             ← ValueSet enums: NullFlavor, ActClass, ActMood …
```

> The class segment is `ClinicalClass`, not `Class` — `Class` is a PHP reserved word and is
> invalid as a namespace segment.

PHP namespaces:

```
Ardenexal\FHIRTools\Component\CdaModels\DataType\
Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\
Ardenexal\FHIRTools\Component\CdaModels\Enum\
```

---

## Class-Level Attribute: `#[LogicalModel]`

Every generated CDA class is tagged with the `LogicalModel` attribute from
`Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel`:

```php
#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
    name: 'ClinicalDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class ClinicalDocument extends InfrastructureRoot { ... }
```

The `xmlNamespace` field is `null` for JSON-capable logical models and non-null for XML-only
targets (all CDA classes use `urn:hl7-org:v3`).

The `refines` field is the canonical URL of the type this definition refines, taken from the SD's
own `type` field — the same signal that chooses the PHP parent. It is omitted for a definition that
introduces a type of its own, which is most of them (179 of the 247 classes: every core CDA type and
37 of the 105 AU ones); the attribute defaults it to `null`, so `name` is already the element name.
Where it is set, `name` is a profile identifier and the element name is the refined type's:

```php
#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ClinicalDocument',
    name: 'au-ClinicalDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
)]
class AuClinicalDocument extends ClinicalDocument { ... }
```

`AuClinicalDocument` serialises as `<ClinicalDocument>`.

Carrying `refines` is necessary but not sufficient for that rename. Pointing `type` at another
published type covers two different things: profiling it, and merely reusing it as a base while
naming an element of your own. AU does both — `asQualifiedEntity` declares `type` as AU's own
`asQualifications`, but `Entity.asQualifiedEntity` and `Person.asQualifications` are two different
elements on two different parents, and `templateId` declares `type` as the `II` datatype, which
names no element at all. Renaming either would put another class's element on the wire.

The serialiser separates the two by the characters in the name. HL7 V3 and CDA build every type and
element name out of `[A-Za-z0-9_]` (`ClinicalDocument`, `templateId`, `IVXB_PQ`), so a name that
contains anything else — the realm prefix in `au-ClinicalDocument` — cannot be an element name and
must resolve through `refines`. A name that is already usable on the wire is kept. This declines
rather than over-reaches: a package whose profile identifiers are plain names would simply stop
being renamed.

Every chain in the current packages resolves in a single hop; the serialiser walks `refines` to the
end regardless, so a future refinement of a refinement needs no change here.

`FhirProperty` is reused unchanged for all property-level metadata (type, cardinality,
`xmlSerializedName`, `isArray`, `isRequired`, etc.).

---

## Package Routing

CDA packages are routed to a dedicated `'CDA'` BuilderContext rather than the R5 context,
because both CDA and FHIR R5 report `fhirVersion: 5.0.0`. Routing is by package name prefix:

| Package name starts with | Routes to |
|---|---|
| `hl7.cda.*` | CDA context |
| `au.digitalhealth.cda.*` | CDA context |
| anything else | R4 / R4B / R5 context as usual |

CDA packages do not require FHIR terminology packages (`hl7.terminology.*`). CDA ValueSets
(NullFlavor, ActClass, ActMood, etc.) are bundled in the CDA package itself.

---

## Implementation Status

| Milestone | Status | Description |
|---|---|---|
| M1 — Foundation | ✅ Done | `#[LogicalModel]` attribute; CDA BuilderContext slot; package routing |
| M2 — Core Generator | Planned | `LogicalModelGenerator`; new `ardenexal/fhir-cda-models` package; class files under `CdaModels/src/DataType/` and `CdaModels/src/ClinicalClass/` |
| M3 — Enums | Planned | PHP enums under `CdaModels/src/Enum/` for NullFlavor, ActClass, ActMood, etc. |
| M4 — AU CDA Schema | Planned | `au.digitalhealth.cda.schema` support; AU classes extend core CDA classes |
| M5 — Serializer | Planned | `urn:hl7-org:v3` namespace on XML root; JSON exception for CDA classes |
| M6 — Quality Gate | Planned | Full integration tests; PHPStan level 8 clean; documentation |

---

## Architecture Decisions

| Decision | Rationale |
|---|---|
| `#[LogicalModel]` not `#[CDAClass]` | Applies to any logical model IG without duplication |
| Separate `'CDA'` BuilderContext | Prevents CDA types polluting the R5 namespace |
| Route by package name, not `fhirVersion` | CDA and FHIR R5 both report `fhirVersion: 5.0.0`; the package name is the only reliable discriminant |
| CDA *identity* = package/canonical URL; *generatability* = generic `kind:logical`+`derivation:specialization` ([ADR-008](../../.goat-flow/learning-loop/decisions/ADR-008-cda-detection-vs-logical-model-generatability.md)) | Keeps `LogicalModelGenerator` IG-agnostic; CDA behaviour layers on top |
| Separate package `ardenexal/fhir-cda-models` ([ADR-009](../../.goat-flow/learning-loop/decisions/ADR-009-cda-models-package-boundary.md)) | Independent (draft/CI) release cadence vs frozen FHIR; disjoint audience; zero type coupling to R4/R5 |
| Reuse `FhirProperty` unchanged | CDA elements use the same SD element structure; `xmlAttr → xmlSerializedName` already works |
| Output to `ClinicalClass/` not `Class/` or `Resource/` | CDA has no concept of FHIR resources; `Class` is a PHP reserved word |

---

## Risk Register

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| CDA V3 type hierarchy has circular `baseDefinition` refs | Low | High | Cycle detection in `LogicalModelGenerator` |
| AU package depends on a core version not yet generated | Medium | Medium | Enforce load/generate ordering; validate parent presence |
| CDA ValueSets use different `compose` structure | Low | Medium | Verify against a real `NullFlavor` ValueSet before M3 |
| `Class` as a PHP namespace segment (reserved word) | — | — | Resolved (ADR-009): the segment is `ClinicalClass`, namespace `…\Component\CdaModels\ClinicalClass\` |
| `au.digitalhealth.cda.schema` not on `packages.fhir.org` | — | — | Confirmed, not a risk: it is on no registry (`packages.fhir.org` and `packages.simplifier.net` both 404). `PackageLoader::KNOWN_PACKAGES` pins the ADHA implementer release tarball and its sha256 — see Package Sources below |
