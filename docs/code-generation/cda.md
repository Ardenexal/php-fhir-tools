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
| `au.digitalhealth.cda.schema` | `1.0.1` | 5.0.0 (R5) | `packages.fhir.org` |

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

CDA output is isolated from all FHIR versions under `Models/src/CDA/`:

```
src/Component/Models/src/
├── R4/
├── R4B/
├── R5/
└── CDA/
    ├── DataType/     ← V3 data types: II, TS, CS, CE, CD, ST, EN, AD, TEL, IVL_TS …
    │                    Base types: ANY, InfrastructureRoot
    ├── Class/        ← CDA act/role/entity/participation classes: ClinicalDocument, Section …
    │                    AU extensions: AuClinicalDocument, AuSubstanceAdministration …
    └── Enum/         ← ValueSet enums: NullFlavor, ActClass, ActMood …
```

PHP namespaces:

```
Ardenexal\FHIRTools\Component\Models\CDA\DataType\
Ardenexal\FHIRTools\Component\Models\CDA\Class\
Ardenexal\FHIRTools\Component\Models\CDA\Enum\
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
| M2 — Core Generator | Planned | `LogicalModelGenerator`; PHP class files under `CDA/DataType/` and `CDA/Class/` |
| M3 — Enums | Planned | PHP enums under `CDA/Enum/` for NullFlavor, ActClass, ActMood, etc. |
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
| Reuse `FhirProperty` unchanged | CDA elements use the same SD element structure; `xmlAttr → xmlSerializedName` already works |
| Output to `CDA/Class/` not `CDA/Resource/` | CDA has no concept of FHIR resources |

---

## Risk Register

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| CDA V3 type hierarchy has circular `baseDefinition` refs | Low | High | Cycle detection in `LogicalModelGenerator` |
| AU package depends on a core version not yet generated | Medium | Medium | Enforce load/generate ordering; validate parent presence |
| CDA ValueSets use different `compose` structure | Low | Medium | Verify against a real `NullFlavor` ValueSet before M3 |
| `Class` as a PHP namespace segment (reserved word) | Medium | Low | Rename to `CDA\ClinicalClass\` if nette/php-generator rejects it |
| `au.digitalhealth.cda.schema` not on `packages.fhir.org` | Low | Medium | Fall back to local package path option in `PackageLoader` |
