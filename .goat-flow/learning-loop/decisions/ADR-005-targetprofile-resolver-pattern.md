# ADR-005: Target Profile Validation via Resolver Interface

**Date:** 2026-05-27
**Status:** Accepted
**Deciders:** Implementation (M08)

## Context

FHIR `type[].targetProfile` constraints require that a `Reference`-typed property points to a resource conforming to a declared profile URL. Validating this requires resolving the `Reference` to an actual PHP object and then checking its `#[FHIRProfile]` attribute.

Two options were considered:

**Option A — `$resolved` property on Reference:** Add a public `?object $resolved` property to the `Reference` class. Callers populate it before validation. The validator reads it directly.

**Option B — Resolver interface (chosen):** Define `FHIRReferenceResolverInterface::resolve(object $reference): ?object`. The validator calls the resolver. A `NullFHIRReferenceResolver` (always returns null → skip validation) is wired as the default.

## Decision

Option B, mirroring M06's `FHIRTerminologyClientInterface` / `NullFHIRTerminologyClient` null-object pattern.

## Rationale

- The generated `Reference` class is a data model; adding a resolution-lifecycle property (`$resolved`) conflates model and resolution concerns.
- `Reference` is generated output (`src/Component/Models/src/`) — hand-editing generated files is prohibited by CLAUDE.md; the resolver would need to live outside the generated class anyway.
- The resolver interface cleanly separates validation infrastructure from model data, matches the existing null-client precedent, and lets consumers supply a real resolver (e.g. container-aware, Bundle-scanning) without changing validation code.
- The null resolver ensures no violations when in-process resolution is not configured, matching extensible/preferred binding behaviour.

## Consequences

- `FHIRReferenceResolverInterface` and `NullFHIRReferenceResolver` are added to `src/Component/Validation/src/`.
- A real resolver implementation is deferred to a follow-on milestone or consumer-provided service.
- When no resolver is wired (or null resolver is active), `#[FHIRTargetProfile]` constraints are silently skipped — no false positives, no false negatives.
- Profile mismatch violations are `ERROR`; unverifiable references (resolved object has no `#[FHIRProfile]`) are `WARNING`.
