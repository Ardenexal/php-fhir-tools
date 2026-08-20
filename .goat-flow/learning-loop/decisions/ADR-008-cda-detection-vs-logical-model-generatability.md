---
date: 2026-06-19
status: accepted
---

# ADR-008: CDA Detection vs Logical-Model Generatability

**Status:** accepted
**Date:** 2026-06-19
**Milestone:** CDA M1 (PR #94) / M2

## Context

CDA R2 and the AU Digital Health schema are published as FHIR **logical models**
(`kind: logical`, `derivation: specialization`). The M1 foundation (PR #94) introduced two
distinct checks in `FHIRModelGeneratorCommand`, which were easy to conflate during review:

- `isCdaPackage($name)` — routes a package to the dedicated `'CDA'` BuilderContext by **name
  prefix** (`hl7.cda.`, `au.digitalhealth.cda.`).
- `loadCdaPackageDefinitions()` — decides whether a CDA package contributes generatable
  StructureDefinitions using the **generic** test `kind === 'logical' && derivation === 'specialization'`.

A review of PR #94 raised the concern that "the isCDA check is not specific to CDA but rather to
logical models." Deep research (25/25 claims confirmed against primary FHIR/HL7 sources) settled
the underlying fact: `kind`, `derivation`, and `baseDefinition` are **general FHIR fields** — they
do not, on their own, identify a model as CDA. CDA identity is carried by the package name and the
canonical-URL prefix (`http://hl7.org/cda/stds/core`, `http://ns.electronichealth.net.au/cda`).

## Options Considered

- **Option A — Treat CDA-ness as a property of the StructureDefinition content** (e.g. sniff
  `kind: logical`): rejected — matches *any* logical-model IG, not just CDA, and would route
  unrelated logical models into the CDA package.
- **Option B — Separate the two concerns explicitly:** package/canonical identity decides CDA
  routing; the generic logical-model test decides generatability.

## Decision

**Option B.** The two concerns are kept distinct and named accordingly:

- **CDA identity** (which BuilderContext/package a definition routes to, which namespace and
  output directory it lands in) is determined by **package name + canonical-URL prefix** —
  never by `kind`. This is the only reliable discriminant because CDA and FHIR R5 both report
  `fhirVersion: 5.0.0`.
- **Logical-model generatability** (whether a StructureDefinition should produce a class at all)
  is determined by the **IG-agnostic** test `kind === 'logical' && derivation === 'specialization'`.

The generator built in M2 (`LogicalModelGenerator`) is therefore a **general** logical-model
generator. CDA-specific behaviour (output namespace, XML-only serialization, `urn:hl7-org:v3`)
is layered on top via the canonical-URL/package identity and the `#[LogicalModel]` attribute's
`xmlNamespace` field — it is not baked into the generatability test.

## Rationale

- The discriminants are orthogonal: a logical model may exist in any IG; CDA is one such IG.
  Conflating them would either mis-route non-CDA logical models or hard-code CDA assumptions into
  a reusable generator.
- It keeps the door open to other logical-model IGs without duplicating the generator, matching
  the existing decision to name the attribute `#[LogicalModel]` rather than `#[CDAClass]`.
- It directly answers the M1 review concern: the *routing gate* is correctly CDA-specific; the
  *generatability test* is correctly general; they must not be merged.

## Consequences

- `LogicalModelGenerator` must not branch on "is this CDA" for generatability — only for identity
  (namespace/output) resolved from the canonical URL.
- M4 (AU schema) inherits this cleanly: AU routes by the `au.digitalhealth.cda.` name prefix and
  the `http://ns.electronichealth.net.au/cda` canonical prefix, and reuses the same generic
  logical-model generation path.
- Tests must cover `loadCdaPackageDefinitions()` routing (a CDA SD lands in `context['CDA']`, a
  terminology-only CDA package returns `[]`) — this was the untested core behaviour flagged in
  the M1 review.
