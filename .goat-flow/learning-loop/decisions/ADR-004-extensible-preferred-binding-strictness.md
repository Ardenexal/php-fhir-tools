# ADR-004: Extensible/preferred binding validation strictness

**Date:** 2026-05-21
**Status:** Accepted

## Context

FHIR binding strengths rank as: required > extensible > preferred > example.

- **required** — code must come from the value set; violation is always an error.
- **extensible** — code should come from the value set; outside codes are allowed only when the value set lacks an appropriate concept.
- **preferred** — the value set is recommended but not enforced.
- **example** — documentation only; no validation.

M06 introduces terminology-server validation for extensible and preferred bindings.
The question is whether consumers should be able to escalate an extensible/preferred
violation from WARNING to ERROR (strict mode).

## Decision

Extensible and preferred binding violations are **always warnings** (`FHIRViolationCode::WARNING`).
No strict-mode opt-in is provided in M06a.

Rationale:
1. Strict mode adds API surface (constraint option, group suffix, or context object) before there
   is evidence of consumer demand. YAGNI applies.
2. FHIR's own conformance model does not make extensible bindings equivalent to required; tooling
   that treats them as errors would break conformant resources.
3. Consumers that need stricter semantics can inspect `ConstraintViolation::getCode()` themselves
   and treat `FHIRViolationCode::WARNING` as an error in their own application layer.

## Consequences

- `FHIRValueSetBindingValidator::validateNonRequired()` always uses `FHIRViolationCode::WARNING`.
- Strict mode is deferred to M06b; the interface `FHIRTerminologyClientInterface` is narrow enough
  that a future `strictMode: bool` constraint option can be added without breaking existing callers.
- A `HttpFHIRTerminologyClient` that actually calls a FHIR server is also deferred to M06b.
