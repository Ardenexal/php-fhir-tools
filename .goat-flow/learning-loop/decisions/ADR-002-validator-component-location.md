---
date: 2026-05-21
status: accepted
---

# ADR-002: Validator Component Location

**Status:** accepted
**Date:** 2026-05-21

## Context

M01 scaffolded `src/Component/Validation/` as the home for Symfony Validator constraint
validators. M02 creates the first concrete validators. This ADR confirms and documents
the layering decision so all future validators follow the same structure.

## Decision

- **Custom constraint *attributes*** belong in `src/Component/Metadata/src/Attribute/Validation/`
  — Metadata is the shared interface/attribute home (established convention from M01).
- **Custom constraint *validators*** belong in `src/Component/Validation/src/`
  — the `ardenexal/fhir-validation` component, scaffolded in M01.
- **FHIRBundle** (`src/Bundle/FHIRBundle/`) wires validators into the Symfony DI container
  via `services.yaml` tags (`validator.constraint_validator`).

## Rationale

- The spike in M01 confirmed Symfony Validator resolves attributes on promoted constructor
  parameters at PHP 8.3 without fallbacks.
- Keeping attributes in Metadata means any downstream component (Serialization, CodeGeneration,
  Validation) can reference attribute classes without introducing circular dependencies.
- Keeping validators in a dedicated Validation component allows the package to ship the
  validator layer independently of the code-generation layer.

## Consequences

- Test files for validators live in `src/Component/Validation/tests/Unit/`.
- PHPStan coverage for the Validation component is configured via the root `phpstan.neon`
  path entry added during M01.
- **Metadata carries a `symfony/validator` dependency** — constraint attribute classes
  (`FHIRValueSetBinding`, `FHIRPathInvariant`, `FHIRFixedValue`, `FHIRPatternValue`) must
  extend `Symfony\Component\Validator\Constraint` to integrate with Symfony's validator
  machinery. Because CodeGeneration references these attribute classes in the model code it
  generates, and CodeGeneration already depends on Metadata, the attributes must live in
  Metadata. This means `src/Component/Metadata/composer.json` requires `symfony/validator`
  even though no *validator logic* (ConstraintValidator subclasses) lives there.
- No `ConstraintValidator` subclass logic belongs in Metadata or CodeGeneration; only
  `Constraint` subclass attribute classes are permitted in Metadata.
