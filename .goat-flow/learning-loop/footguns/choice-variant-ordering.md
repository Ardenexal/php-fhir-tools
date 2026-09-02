---
category: choice-variant-ordering
last_reviewed: 2026-08-07
---

# Footguns: Choice Variant Ordering

## Footgun: choice variants are matched by `instanceof` in declaration order, so a superclass listed before its subclass steals the match

**Status:** active | **Created:** 2026-08-07 | **Evidence:** OBSERVED (operation-codegen M01)

When serializing a `value[x]` choice, `AbstractFHIRNormalizer::resolveChoiceVariant`
(`src/Component/Serialization/src/Normalizer/Common/AbstractFHIRNormalizer.php`, search:
`function resolveChoiceVariant`) walks `$meta->variants` **in declaration order** and returns the
first match. For non-builtin variants the test is `$value instanceof $variant->phpType`.

The generated primitive wrappers form real inheritance chains
(`src/Component/Models/src/{R4,R4B,R5}/Primitive/`):

| Subclass | extends |
|---|---|
| `CodePrimitive`, `IdPrimitive`, `MarkdownPrimitive` | `StringPrimitive` |
| `CanonicalPrimitive`, `OidPrimitive`, `UrlPrimitive`, `UuidPrimitive` | `UriPrimitive` |
| `PositiveIntPrimitive`, `UnsignedIntPrimitive` | `IntegerPrimitive` |

So if a superclass variant is declared **before** its subclass, the subclass's values match the
superclass first and serialize under the wrong `value[x]` key — silently, with no error and a
structurally valid-looking result.

**The trap is specifically alphabetical sorting.** It looks like a safe canonicalisation and is
correct for most pairs by luck (`code` < `string`, `canonical` < `uri`, `id` < `string`), which is
why it survives casual testing. It is wrong for at least one real pair:

- allowed types `{uri, url}` sort to `uri, url`, but `UrlPrimitive extends UriPrimitive` — a
  `UrlPrimitive` then emits as `valueUri`.

`{integer, positiveInt}` sorts wrongly too but is saved by accident: `integer` is a *builtin*
variant, so it takes the `gettype()` branch, which an object never satisfies.

**Rule:** any code that builds a `variants` list must order it **subclass-before-superclass**, not
alphabetically. Sorting type *codes* for comparison is fine — `AllowedTypeReader::read()`
(`src/Component/CodeGeneration/src/Parser/AllowedTypeReader.php`) sorts deliberately so R4/R4B/R5
compare equal — but that sorted list must not be used as the emitted variant order.

Directly relevant to operation code generation M02, which will build variant lists for every
operation in three FHIR versions. `CodeSystem/$lookup` happens to be safe; do not generalise from it.

## Footgun: building `Models\{version}\...` FQCNs by hand silently defeats profiles

**Status:** active | **Created:** 2026-08-07 | **Evidence:** OBSERVED (operation-codegen M01, user review)

Interpolating a model class name from a version — `"Ardenexal\FHIRTools\Component\Models\{$version}\Resource\{$type}Resource"`
— resolves only base-spec classes. It cannot see:

- a profile class registered via `FHIRTypeResolver::addResourceTypeMapping()`, or
- an IG profile discovered through `meta.profile` in `FHIRIGTypeRegistry::resolveProfileClass()`.

The failure is silent and asymmetric: the normalizers *do* go through
`FHIRTypeResolver::resolveResourceType()` (`src/Component/Metadata/src/Type/FHIRSerializedTypeResolver.php`,
search: `function resolveResourceType`), so they honour the profile while the hand-built path
produces the base class. Nothing errors; the two halves just disagree about what type is in play.

**Fix:** resolve through `FHIRTypeResolverInterface`, then read further types off the *resolved*
class's own `#[FhirProperty]` metadata rather than building more names. `OperationParameterMapper`
(`src/Component/Serialization/src/Operation/OperationParameterMapper.php`) does this in three steps:
`Parameters` from the resolver → its backbone from that class's `parameter` property (`phpItemClass`)
→ primitive wrappers from that backbone's `value[x]` variants. The last step also removed a
hand-written builtin-type table: `PropertyVariantMetadata::$isBuiltin` already records which types
skip wrapping, so the table could not drift from the models.

Proven by mutation: reinstating the hardcoded FQCN fails
`OperationParameterMapperTest::testARegisteredProfileClassIsUsedInsteadOfTheBaseResource`.
