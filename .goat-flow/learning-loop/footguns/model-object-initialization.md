---
category: model-object-initialization
last_reviewed: 2026-08-31
---

# Footguns: Partially-Initialized Generated Model Objects

## Resolved Entries

## Footgun: getExtensionUrl() throws Error on constructor-bypassed Extension objects

**Status:** resolved | **Created:** 2026-06-02 | **Resolved:** 2026-08-31 | **Evidence:** OBSERVED (M11, RUNTIME)

`FHIRExtensionInterface::getExtensionUrl()` documents "returns null when the url property
has not been set" — but the generated implementation (`src/Component/Models/R4/DataType/Extension.php`,
search: `getExtensionUrl`) is `return $this->url;` on a **promoted typed property**. The
constructor default (`?string $url = null`) only applies when the constructor runs.
Deserializers instantiate via `newInstanceWithoutConstructor`, leaving `$url`
uninitialized, so the getter throws
`Error: Typed property ...Extension::$url must not be accessed before initialization`
instead of returning null.

Observed impact: M11's nested-extension descent called `getExtensionUrl()` on every
extension; spec-suite cases `patient-extension-bad3` and `versioned-extension` flipped
pass→skip because `FHIRValidatorSpecificationTest` converts a validation `Error` into a
skip (search: `Validation threw Error`). The whole-suite pass count drop was the only
visible symptom — no failure, no error.

**Resolved (2026-08-31, issue #107):** fixed at source rather than guarded at each call site.
`AbstractFHIRNormalizer::constructorDefaults()` (search: `constructorDefaults`) read only the
most-derived constructor, so any parameter a subclass supplies to `parent::__construct()` rather
than re-declaring resolved to no default and was left uninitialized. It now merges constructor defaults across the whole class hierarchy, most-derived
winning. Residual uninitialized surface across R4/R4B/R5/CDA fell 5260 → 340, and every one of the
340 remaining is a non-nullable parameter with genuinely no declared default. Regression cover:
`MinimalPayloadDeserializationTest` (search: `testTypedExtensionWithAbsentValueReadsAsNull`).

The historical mitigation — guarding reads with `isset` or `FHIRValidationService::readExtensionUrl()`
(search: `readExtensionUrl`) — is no longer required for deserializer-origin objects and is retained
only for objects built by other means. Note that `isset` guards *cannot detect* this defect, which is
why it survived so long: `isset()` and `??` return false/fallback on an uninitialized slot without
throwing, so only a direct read or `=== null` exposes it.

## Footgun: Extension::$value was left uninitialized on deserializer-origin objects

**Status:** resolved | **Created:** 2026-06-04 | **Resolved:** 2026-08-31 | **Evidence:** OBSERVED (M13, RUNTIME)

Same root cause as `$url` above, for the value carrier. `Extension::$value` (search:
`$value = null` in `src/Component/Models/src/R4/DataType/Extension.php`) is a promoted typed
property; on deserializer-origin objects it was left uninitialized, so a bare `$extension->value`
threw `Error: Typed property ...Extension::$value must not be accessed before initialization`.

**Resolved (2026-08-31, issue #107)** by the same hierarchy-walk fix described above. The reachable
case was narrower than the entry implied and is worth recording: a *typed* extension subclass
(`MinLengthExtension` and friends) declares `id` and its own `value<Type>`, but passes `url`, and
the inherited `value` it narrowed, to `parent::__construct()` as computed arguments, so those two
were the slots a child-only lookup missed. `url` therefore appeared to work because every extension
payload carries one, and `value` appeared to work whenever the payload supplied a value — so the
defect surfaced only for an extension whose optional value was **absent from the payload**. Any
fixture that populated the value kept the suite green.

## Footgun: bare builtin-scalar leaves (`Resource.id`) reject deserializer-wrapped primitive answers; programmatic tests mask it

**Status:** resolved | **Created:** 2026-07-10 | **Evidence:** OBSERVED (sdc-extract M02, RUNTIME)

Most FHIR primitive leaves are typed as a wrapper (`?StringPrimitive`) or a union
(`StringPrimitive|string`), so a `QuestionnaireResponse` answer deserialized as a `StringPrimitive`
assigns cleanly. But a handful of elements are modeled as a **bare builtin scalar** — notably
`Resource.id` (`public ?string $id`, fhirType `http://hl7.org/fhirpath/System.String`; see
`AbstractResource`, search: `public ?string $id`). Writing a definition path to such a leaf
(`Patient#Patient.id`) with a deserializer-origin answer throws
`TypeError: Cannot assign ...StringPrimitive to property PatientResource::$id of type ?string` —
the deserializer wraps the answer, but the leaf wants the raw scalar.

The trap is invisible in programmatic tests: constructing the QR with `answer: value: 'pat-42'`
(a raw string) assigns fine, so a unit test that builds inputs by hand **passes while the real
deserialized path fails**. The `$extract` PUT branch shipped green against a programmatic unit test
and only broke when an integration test fed JSON fixtures through `FHIRSerializationService`
(`FHIRExtractConformanceTest::testDefinitionExtractWithLogicalIdProducesPutDirective`).

Mitigation: the path writer now unwraps a primitive-wrapper answer to its inner scalar when — and
only when — the target leaf's declared types are exclusively builtin scalars
(`DefinitionPathWriter::unwrapForBuiltinLeaf`, the inverse of `coerceScalar`); union leaves like
`StringPrimitive|string` keep their wrapper untouched. **Lesson: prove extraction/writing through
the real deserializer (JSON fixtures), not just programmatically-built model objects — raw-scalar
inputs mask primitive-wrapper type mismatches.**

## Footgun: choice-valued (`value[x]`) extensions need `variants` metadata or the value is silently dropped

**Status:** resolved | **Created:** 2026-06-04 | **Evidence:** OBSERVED (M13, RUNTIME)

A typed extension subclass whose `value[x]` permits several types (e.g. `MinValueExtension`,
`MaxValueExtension`, `MaxDecimalPlacesExtension`) declares its `value` constructor param with
`#[FhirProperty(... isChoice: true, variants: [...])]`. The normalizer's choice index
(`AbstractFHIRNormalizer::findChoicePropertyByKey`, search: `findChoicePropertyByKey`) needs
`variants` to map an incoming `valueDate`/`valueInteger` element onto the choice property —
**without `variants` the value deserializes to nothing, no error.** Scalar-valued typed
extensions (`MinLengthExtension`, `RegexExtension`, `QMin/MaxOccursExtension`) are immune because
they declare a concrete `value<Type>` property instead.

Fixed in the generator (`FHIRExtensionGenerator::buildMultiTypeValueConstructor`, search:
`buildMultiTypeValueConstructor`), which now emits `variants` for the choice param — but the trap
recurs for any NEW choice-valued extension if that emission path regresses. Regression test:
`FHIRExtensionGeneratorTest::testMultiTypeValueExtensionEmitsChoiceVariants`. After any generator
change, **regenerate AND run `pint`** — raw `fhir:generate` output is compact inline-FQCN code; an
un-linted diff looks like thousands of changed files (the real change is far smaller).
