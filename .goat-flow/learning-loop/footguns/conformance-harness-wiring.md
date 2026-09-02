---
category: conformance-harness-wiring
last_reviewed: 2026-08-31
---

# Footguns: Conformance Harness Wiring

## Footgun: a validator pass gated on an optional collaborator measures zero and reads as a capability gap

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M07, RUNTIME)

`FHIRValidationService::__construct()` takes `?FHIRIGTypeRegistry $registry = null`, and the whole
extension pass is gated on it (`src/Component/Validation/src/FHIRValidationService.php`, search:
`if ($this->registry !== null)`). Until 2026-08-31 `OracleValidationServiceFactory::create()` never
passed one, so in **every** run of `compare-java-outcomes.php` that pass was skipped entirely.

The damage is that nothing says so. The comparison reports `profile:extension` findings as missing,
the capability histogram shows a large number, and a milestone gets sized against it — when the rule
that would close those findings may already exist and simply never ran. In M07 this hid the fact that
the shipped unknown-*modifier*-extension rule had never once been exercised by the corpus comparison.

**Symptom to watch for:** a capability whose measured size is suspiciously round or suspiciously
untouched by prior work, on a rule you can find in the validator source.

**Mitigation:** before sizing or trusting any capability number, confirm the pass that produces it is
reachable in the *harness wiring*, not merely present in the validator. Read what
`OracleValidationServiceFactory` passes, then grep `FHIRValidationService` for `!== null` gates on that
collaborator. The same shape can hide any optional dependency —
`FHIRTerminologyClientInterface`/`NullFHIRTerminologyClient`, reference resolvers, type resolvers.

**Second-order effect:** wiring a missing collaborator in activates already-shipped rules for the first
time, so cases can move into `ABOVE` with no change to any rule. Wiring the registry alone put 14 R4
cases into `ABOVE`. Measure a clean baseline in the same session *before* the wiring change, or the
regression looks like it came from the new code.

## Footgun: the reference validator is given per-case IG packages we do not load, so "we cannot resolve it" is not a finding

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M07, RUNTIME)

`vendor/fhir/fhir-test-cases/validator/manifest.json` configures the reference validator per case, and
four of its keys hand it extension or profile definitions this project never loads:

- `packages` — IG packages loaded for that case (`obs-de` loads four German profile packages)
- `allowed-extension-domain` — a domain whose extensions are accepted with **no definition at all**
  (`uk-msg` declares `https://fhir.nhs.uk/StructureDefinition`)
- `profile` / `supporting5` — StructureDefinitions supplied beside the instance
- `module: xver` — cross-version extensions resolved structurally, not looked up

A rule of the form "we could not resolve X, so report it" fires on all of these and produces pure
false positives, because the reference validator *could* resolve them. `ComparisonHarness::
referenceHadExtensionSourcesWeLack()` (search: `referenceHadExtensionSourcesWeLack`) reads those keys
and turns extension resolution off for those cases only.

Note it is a **comparability gate, not a skip**: the case stays in the compared set and every other
rule keeps measuring it. Dropping the case instead would shrink the denominator and read as progress.

**And the gate has a floor.** It cannot reach zero false positives, because the reference validator is
inconsistent independently of the manifest: `q-bp` and `questionnaire-enableWhen-dw` have identical
manifest key sets, yet Java reports 17 unknown-extension errors on the first and none on the second.
Do not keep widening a gate to chase those — at that point the difference is behavioural, and the
honest move is a declared limitation. See
[decisions/ADR-014](../decisions/ADR-014-unresolvable-regular-extensions.md).
