---
category: generated-model-nullable-cardinality
last_reviewed: 2026-07-11
---

# Footguns: generated FHIR models — every field is nullable, so cardinality is not enforced at construction

## Footgun: a cardinality-incomplete resource constructs, passes PHPStan, and serializes — but is invalid FHIR

**Status:** active | **Created:** 2026-07-11 | **Evidence:** OBSERVED (sdc-extract M04, Provenance emission)

The generated model classes (`src/Component/Models/src/{R4,R4B,R5}/`) make **every** constructor
parameter nullable/defaulted, even elements the spec marks required (`min: 1`). The required-ness is
recorded only as metadata attributes (`#[FhirProperty(isRequired: true)]`, `#[Count(min: 1)]`,
`#[NotBlank]`) and as Symfony validator constraints — **not** as PHP type constraints.

Consequence: when you build a resource programmatically (e.g. `ExtractModelFactory::provenance()`), you
can omit a required element and:

- construction succeeds (the param defaults to `null`/`[]`),
- PHPStan level 8 stays green (the property type genuinely accepts null),
- serialization emits a resource that simply lacks the element.

The result is **invalid FHIR that no static check catches.** A test asserting only "a Provenance object
exists" or "the entry has `resourceType: Provenance`" passes against a resource missing `target`,
`recorded`, or `agent.who` (all `min: 1` in R4).

**Concrete case:** the SDC extraction spec's Provenance guidance names only `entity.what` + `role = source`.
Building exactly that yields a Provenance with no `target`/`recorded`/`agent` — structurally invalid.
`ExtractModelFactory::provenance()` (search: `public function provenance`) now populates all four; the fix
was *not* discoverable from PHPStan.

## Mitigation

- When emitting a FHIR resource programmatically, **validate a representative instance at runtime** with
  the toolkit validator (`mcp__symfony-ai-mate__fhir-validate`, or `FHIRValidator`), and make that the
  proof — not "the object was constructed" or "PHPStan is green". Provenance emission was confirmed with
  `isValid: true, errorCount: 0` (only the `dom-6` narrative best-practice warning).
- Read the target resource's generated constructor and grep for `isRequired: true` / `#[Count(min:` /
  `#[NotBlank]` before assuming the minimal object you built is spec-legal.
- Prefer a builder that sets every required element up front (see ADR-010 Decision 4) over incrementally
  adding fields until a test passes — the test is a weak oracle here.
