---
date: 2026-05-21
status: accepted
---

# ADR-003: Binding URL → PHP FQCN Runtime Mapping

**Status:** accepted
**Date:** 2026-05-21

## Context

`FHIRValueSetBindingValidator` must check whether a property value is a valid case of a
generated PHP enum class. Given a value-set URL (e.g.
`http://hl7.org/fhir/ValueSet/observation-status`) it must derive a fully-qualified PHP
class name (e.g. `Ardenexal\FHIRTools\Component\Models\R4\Enum\ObservationStatus`).

Two candidates from the M02 plan were evaluated:

1. **Move `ClassNameResolver` to `src/Component/Metadata/src/`**
   — `ClassNameResolver` lives in CodeGeneration and carries a CodeGeneration-specific
   override list (`claim-use → ClaimUse`). Moving it to Metadata couples the shared
   interface layer to a generator implementation detail.

2. **Duplicate the naming logic in `src/Component/Validation/src/`**
   — The core algorithm is a single line: `ucwords(str_replace(['-','_'],' ',$basename))`.
   The CodeGeneration override list is not needed at validation time because the validator
   uses `class_exists()` probing rather than deterministic naming.

## Decision

**Option 2 — duplicate a minimal pascal-case naming function inside Validation.**

`FHIRValueSetBindingValidator` receives a `string[] $enumNamespaceRoots` constructor
argument (e.g. `['Ardenexal\FHIRTools\Component\Models\R4\Enum']`). The validator
iterates namespace roots, probes `class_exists("{$root}\\{$className}")`, and uses the
first match.

FHIRBundle injects the namespace roots for all registered FHIR versions via `services.yaml`.
Unit tests pass a stub namespace root array directly.

### Fallback for unresolvable required-strength bindings

If no enum class is found for a `required`-strength binding, the validator **must emit an
`ERROR` violation** identifying the unresolvable value-set URL. Silently passing is
semantically incorrect for `required` cardinality bindings.

## Rationale

- Avoids moving CodeGeneration-specific overrides into Metadata.
- Keeps the Validation component free of CodeGeneration and Models dependencies.
- The naming algorithm (`basename + pascal-case`) is a 3-line private method — not worth
  a shared dependency for this alone.
- Injecting namespace roots as constructor arguments makes the validator testable without
  a live model package.

## Consequences

- `ClassNameResolver` stays in CodeGeneration unchanged.
- `FHIRValueSetBindingValidator` has a `string[] $enumNamespaceRoots` constructor parameter.
- FHIRBundle `services.yaml` must enumerate the namespace roots for all FHIR versions
  it registers (R4, R4B, R5 as applicable).
- If the project generates a custom IG with a non-standard namespace, the bundle
  configuration must be extended to include that namespace root.
