---
category: operation-allowed-type-sources
last_reviewed: 2026-08-20
---

# Footguns: Operation Allowed-Type Sources

## Footgun: the R5 element that supersedes the allowed-type extension is empty in every shipped package, so reading the spec-correct source yields zero variants

**Status:** active | **Created:** 2026-08-20 | **Evidence:** OBSERVED (operation-codegen M01)

An `OperationDefinition` parameter typed `Element` or `*` is polymorphic, and the set of concrete
types it permits has **two** possible sources:

1. `parameter.allowedType[]` — a first-class element **added in R5**, and the one the specification
   presents as the way to express this.
2. `extension[url = http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type].valueUri`
   — the pre-R5 mechanism, which R5 does not remove.

Reading the spec-obvious source gets you nothing. **No shipped `OperationDefinition` in R4, R4B or
R5 populates `allowedType`** — every definition carrying allowed-type information uses the extension,
R5 included. So an implementation that reads `allowedType` alone returns zero variants for *every*
polymorphic parameter in *every* version.

The correction that looks right is also wrong, and is worse: branching on version — extension for
R4/R4B, `allowedType` for R5 — reads the empty source precisely where the packages have nothing, so
R5 `$lookup`'s `property.value` silently resolves to **no variants at all** while R4 works. A
version-branched reader looks more careful than a unioning one and fails only on the newer version,
which is the harder direction to notice.

**Rule:** union both sources on all versions. `AllowedTypeReader::read()`
(`src/Component/CodeGeneration/src/Parser/AllowedTypeReader.php`, search: `ALLOWED_TYPE_URL`) does
this, deliberately without a version branch. R5's `allowedType` binds to the same value set as
`parameter.type` (`fhir-types|5.0.0`), so one type-resolution path serves both, and keeping the
element in the union is forward compatibility for when packages do start populating it.

**Why a variant set of `[]` must not be treated as a supported shape.** Measured across all
OperationDefinitions in the three cached core packages, including nested `part[]`:

| Version | Total params | `type` is `Element`/`*` | With an allowed-type source | Without |
|---|---|---|---|---|
| R4 | 278 | 6 | 6 | **0** |
| R4B | 278 | 6 | 6 | **0** |
| R5 | 376 | 10 | 10 | **0** |

There is no "polymorphic but unconstrained" case in the core packages. An empty variant set for a
parameter whose `type` is `Element` or `*` is therefore a **defect or a non-core package**, not a
shape to emit an untyped property for. (An empty set for a *monomorphic* parameter is the normal,
expected case.)

**Evidence:** `src/Component/CodeGeneration/tests/Unit/Parser/AllowedTypeReaderTest.php`
(search: `testNoShippedDefinitionPopulatesTheAllowedTypeElement`) asserts the divergence.
`testAllVersionsResolveTheSameVariantSetForPropertyValue` pins the union's result at seven variants
identically across R4, R4B and R5 — the assertion a version-branched reader fails.

**Correction, 2026-08-20 (goat-review of PR #104).** This entry previously claimed that test "asserts
the divergence directly against the real packages, so it fails if a future package bump starts
populating the element." **It does not.** `AllowedTypeReaderTest::parameterAtPath()` (search:
`Fixtures/OperationDefinitions/%s-CodeSystem-lookup.json`) reads a *committed copy* of the
OperationDefinition, not the package cache. The test's own docblock is honest about this — it says
"because a fixture populates `allowedType`" — so the inaccuracy was in this footgun record, which is
the artefact a future maintainer would trust.

What that means in practice: **a package bump cannot fail this assertion.** The 8 committed
`OperationDefinitions/*.json` copies (3 in `CodeGeneration/tests/Fixtures/`, 5 in
`Serialization/tests/Fixtures/`) have no producer — `seed-operation-fixtures.php` writes only
`OperationManifests/` and `TypeIndex/` — and `OperationFixturesMatchPackagesTest` compares only
those two, and is itself skipped in CI (no FHIR cache there). The manifests additionally drop
`extension`/`allowedType` from `PARAMETER_FIELDS`, so even when that drift test does run it cannot
cover the allowed-type union.

So the union assumption above is pinned against a snapshot, not against HL7. Before trusting it after
any package bump, re-derive by hand: decode the shipped `OperationDefinition-CodeSystem-lookup.json`
from the cache and compare `parameter.allowedType` against the committed copy. A cross-tree equality
test now at least guarantees the duplicated copies agree with each other
(search: `OperationDefinitionFixturesAgreeAcrossComponentsTest`).

## Footgun: spec prose describing a newer mechanism is not evidence the shipped packages use it

**Status:** active | **Created:** 2026-08-20 | **Evidence:** OBSERVED (operation-codegen M01)

The generalisation of the entry above, and the reusable part. FHIR's specification prose, its
published examples, and the content of the packages it ships are three separate artefacts that can
disagree, and the prose is the *least* reliable guide to what a definition in a package actually
contains.

Two instances found in one milestone:

- `parameter.allowedType` is documented as R5's way to express polymorphic parameters and is empty in
  every shipped R5 definition (above).
- The specification's own published `$lookup` example is defective as a conformance oracle — it
  contains a parameter the `OperationDefinition` never declares. A round-trip test seeded from it
  fails for a reason that has nothing to do with the code under test.

**Rule:** when a code path depends on what a definition contains, read the cached package and assert
on it — do not infer from prose or from an example. A test that reads the real package (like
`AllowedTypeReaderTest` above) also catches the day the packages change, which no amount of reading
the spec will.

**Evidence:** `src/Component/Serialization/tests/Unit/Operation/OperationSpecExampleRoundTripTest.php`
(search: `testPublishedExampleRoundTripsApartFromTheUndeclaredParameter`) — the published example is
used as a fixture only after the undeclared parameter is subtracted from the expectation, and the
subtraction is what keeps the assertion honest.

Corollary on research method: this question survived a 103-agent web research sweep unanswered, then
was settled by one direct fetch of the normative page plus one grep over the cached packages. Prefer
the primary artefact over aggregated search.
