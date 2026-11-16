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

---

## Amendment, 2026-08-20

**The Decision above stands.** Extensible and preferred binding violations are still always
`FHIRViolationCode::WARNING`, and `FHIRValueSetBindingValidator::validateNonRequired()` still uses it.
What has changed is two of the Consequences, both of which read as current state and are no longer true.

### `HttpFHIRTerminologyClient` is no longer deferred — it exists

The last Consequence bullet says a client that actually calls a FHIR server "is also deferred to M06b".
It was built. `src/Component/Validation/src/` now holds `HttpFHIRTerminologyClient`,
`HttpFHIRTerminologyClientFactory`, `CachingFHIRTerminologyClient`, `InMemoryFHIRTerminologyClient` and
`PreferredServerAwareTerminologyClient` beside the null one.

What remains true is the **default**: `src/Bundle/FHIRBundle/src/Resources/config/services.yaml` aliases
`FHIRTerminologyClientInterface` to `NullFHIRTerminologyClient`, with the HTTP client documented there as
an opt-in override a consuming application registers itself. So a stock installation still performs no
code lookups — but the reason is the default wiring, not a missing implementation.

This matters when citing the ADR as a blocking reason. "No terminology client exists" is wrong; "the
default wiring resolves to the null client, and the conformance comparison runs offline" is right.
Found while writing declared limitations that name this decision, which would have propagated the stale
claim into a durable record.

### Strict mode is still absent, and there is now evidence of demand

The Decision declined a strict-mode opt-in for want of consumer demand. That reasoning was sound and the
outcome is unchanged, but the evidence position has moved: comparing against the HL7 reference validator
on the vendored corpus shows warning counts differing on 97 R4, 1 R4B and 45 R5 cases, largely because it
performs lookups we decline to. That is not by itself a reason to add strict mode — the divergence is a
consequence of running offline rather than of the severity choice — but a future revisit has a measured
starting point instead of none. The figures are pinned by
`MissingFindingMeasurementTest::testWarningDivergenceIsPinned`.

### A separate finding, recorded here because it will be mistaken for this decision

Display validation is blocked by something else entirely, and reading this ADR alone would attribute it
here. The validator resolves codes against **generated enums** (`FHIRValueSetBindingValidator` takes
`$enumNamespaceRoots` pointing at `Models\{R4,R4B,R5}\Enum`) rather than against the package cache under
`~/.fhir/packages`, which only `CodeGeneration` reads. A generated enum carries the display as a docblock
comment — `/** Male */` above `case male = 'male';` — so there is no display text to compare against at
runtime, for any code system, licensed or not. Closing display findings needs the generator to emit
displays as data, or a package-backed lookup at validation time. Neither is a terminology-server question.
