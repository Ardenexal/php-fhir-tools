---
date: 2026-06-19
status: accepted
---

# ADR-009: CDA Models Live in Their Own Composer Package

**Status:** accepted
**Date:** 2026-06-19
**Milestone:** CDA M2 (prerequisite — fixes the output path/namespace before any class is emitted)

## Context

This repo is a monorepo split into per-component Composer packages (the `replace`/path pattern).
Generated FHIR models already ship as their own sub-package:

- `ardenexal/fhir-models` — pre-generated R4/R4B/R5 classes, ~5,955 files / ~33 MB, namespace
  `Ardenexal\FHIRTools\Component\Models\`, requires only `ardenexal/fhir-metadata` +
  `brick/date-time`, declared `minimum-stability: stable`.

The CDA spec (`docs/code-generation/cda.md`) and the M1 foundation originally assumed CDA output
would live *inside* that package at `Models/src/CDA/` (namespace `…\Component\Models\CDA\`).

Two facts make the boundary worth reconsidering before M2 emits anything:

1. **CDA models have zero cross-references to FHIR R4/R5 types.** The `CDA` output is a
   self-contained HL7 V3 lattice (ANY → datatypes → act/role/entity classes). Nothing technical
   binds it to the FHIR version models.
2. **CDA and FHIR R4/R5 sit on different stability tiers.** R4/R4B/R5 are frozen and rarely
   regenerate. Deep research confirmed `hl7.cda.uv.core` is a *continuous CI build* (versions
   observed: 2.0.0-sd → 2.0.2-sd → 2.0.3-sd → 2.1.0-draft1) and the AU schema is draft-tier.

Generation is still a no-op at M1, so **no CDA namespace has been emitted yet** — the cost of
choosing the boundary now is zero; the cost of changing it after M2 is a namespace migration over
hundreds of generated files plus serializer reference updates.

## Options Considered

- **Option A — Bundle into `ardenexal/fhir-models`** at `Models/src/CDA/` (the original plan).
  Simplest: one package, one split target, one release. But couples a moving/draft target to a
  stable artifact, forces stable-package churn on every CDA regen, and ships CDA classes to every
  FHIR-only consumer.
- **Option B — Separate package `ardenexal/fhir-cda-models`** as a new monorepo component
  (`src/Component/CdaModels/`), namespace `Ardenexal\FHIRTools\Component\CdaModels\`, requiring
  `ardenexal/fhir-metadata` (mirroring `fhir-models`).

## Decision

**Option B — CDA models ship as a separate package `ardenexal/fhir-cda-models`.**

- New monorepo component: `src/Component/CdaModels/` with its own `composer.json`.
- Namespace: `Ardenexal\FHIRTools\Component\CdaModels\` with sub-namespaces `DataType\`,
  `ClinicalClass\` (see note), and `Enum\`.
- Output tree: `src/Component/CdaModels/src/{DataType,ClinicalClass,Enum}/`.
- Dependencies: `php >=8.3`, `ardenexal/fhir-metadata` (for `#[LogicalModel]`/`FhirProperty`),
  and `brick/date-time` if temporal datatypes need it — no dependency on `ardenexal/fhir-models`.

> **`Class` reserved-word note:** the original spec used a `Class/` segment. PHP namespace
> segments cannot be the reserved word `Class`. The CDA act/role/entity classes therefore live
> under `ClinicalClass\` (this also resolves the M1 risk-register item).

## Rationale

- **Independent release cadence / stability** (primary): a draft, CI-built, fast-moving target
  should not force version bumps and re-releases of the frozen, stable FHIR models package.
- **Disjoint audiences + footprint:** most `fhir-models` consumers want R4/R5 and will never load
  CDA; they should not pay for it on install.
- **Zero technical coupling:** the `CDA` namespace references no FHIR version types, so
  co-location buys nothing.
- **Free now, expensive later:** deciding before M2 avoids migrating the emitted namespace and
  every serializer reference after the fact.
- The current "all FHIR versions in one models package" precedent holds *because R4/R4B/R5 share a
  stability tier* — CDA does not, which is exactly the seam to split on.

## Consequences

- M2 emits to `src/Component/CdaModels/src/` under `…\Component\CdaModels\`, not `Models/src/CDA/`.
  The M1 `'CDA'` BuilderContext slot, `isCdaPackage()` routing, and `ensureTerminologyPackages()`
  bypass are **unaffected** — only the generated output path/namespace and a new `composer.json`
  change.
- `docs/code-generation/cda.md` is updated to the separate-package layout (output structure,
  namespaces, status table, architecture decisions).
- The serializer (M5) reads the XML namespace from `#[LogicalModel]->xmlNamespace` regardless of
  package boundary, so this decision does not affect serialization logic.
- Adds one monorepo component, one Composer package, and one CI split target — accepted as a
  modest, one-time infrastructure cost.
- Supersedes the `Models/src/CDA/` and `…\Component\Models\CDA\` layout described in earlier
  drafts of `cda.md`.
