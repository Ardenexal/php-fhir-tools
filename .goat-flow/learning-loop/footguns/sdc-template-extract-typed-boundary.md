---
category: sdc-template-extract-typed-boundary
last_reviewed: 2026-07-11
---

# Footguns: SDC template extraction — decoded-array vs typed-model boundary

## Footgun: the typed deserializer rejects a scalar landing on a complex-typed element (e.g. a bare-string `Reference`)

**Status:** active | **Created:** 2026-07-11 | **Evidence:** OBSERVED (M03 SDC template `$extract`)

SDC template-based extraction mutates a `contained` template as untyped JSON (clone → substitute
FHIRPath results → prune), then must hand the result back as a **typed** model to enter the transaction
Bundle (`ExtractResult::getResource()` is a typed `BundleResource`, and every SDC test serialises it).
The trap: a `templateExtractValue` can write a **scalar directly onto a complex-typed element**. The SDC
IG `extract-complex-template` does exactly this for `Observation.subject` (the value extension sits on
`subject` itself, not on `_reference`), so the reference engine `@aehrc/sdc-template-extract` emits a
**malformed bare-string** `"subject": "<uuid>"`. Feeding that array to
`FHIRSerializationService::deserializeFromJson(..., ObservationResource::class)` throws:

```
Could not denormalize object of type "…\R4\DataType\Reference", no supporting normalizer found.
```

There is no scalar→`Reference` normalizer — a scalar where a datatype object is expected is a hard
deserialize failure, and **PHPStan cannot see it** (`deserializeFromJson` takes `class-string`/`string`,
so a wrong value shape is a runtime error, never a static one). Pure array-native output does not dodge
this: the array must still become a typed `BundleResource`, hitting the same wall — and emitting the
bare string as "valid output" would be a product defect (any typed consumer breaks identically).

**Mitigation (M03):** keep the array-level mutation, but before deserialisation wrap a scalar that lands
on a complex root property into that element's shape using `PropertyMetadataProvider` — `Reference` →
`{reference: X}` (`TemplateExtractor::wrapComplexTargets`, search: `wrapComplexTargets`). This emits
**valid** FHIR; the conformance oracle reconciles the shape divergence by unwrapping a sole-key
`{reference: X}` → `X` on both sides (`FHIRExtractConformanceTest::reconcileTemplateBundle`, search:
`unwrapSoleReference`). The wrap is deliberately narrow (Reference root properties only); broaden it if a
future template writes a scalar onto another complex element.

Related traps proven the same session:
- A `templateExtractContext` result may be a **scalar node** (`answer.value` → a string), which must
  still become the FHIRPath focus for that element's value expressions — do not filter context nodes to
  objects (`TemplateExtractor::transformList`, search: `contextExpr`).
- `templateExtractValue` empty semantics: an empty result **drops the value wrapper but keeps any static
  placeholder** (`effectiveDateTime` survives; `issued`/`performer` vanish). Empty means the FHIRPath
  collection is **count 0**, NOT a falsy value — a `false`/`0` result is present and must be preserved.
- Numeric decimals serialise as PHP floats (`110.0`) where the reference emits ints (`110`); the SDC
  conformance base compares with PHPUnit `assertEquals` (loose), so `110 == 110.0` passes — a strict
  `===` comparison would spuriously fail. See [[fhirpath-evaluation-context-mutation]] for the
  `%resource`/focus split this builds on.
