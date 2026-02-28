# FHIRPath Type Loss — Investigation & Fix Plan

## Background

The FHIRPath specification conformance suite (`composer test-ai-fhirpath-spec`) reveals a large
number of failures related to type identity. Investigation identified three distinct root causes
across two components (Serialization, FHIRPath) that together explain the failures.

---

## Root Cause A — Serialization: `array`-typed properties bypass the denormalizer

**Files affected**
- `src/Component/Serialization/src/Normalizer/AbstractFHIRNormalizer.php` (`isBuiltinType`)
- `src/Component/Serialization/src/Normalizer/FHIRResourceNormalizer.php` (`denormalizeFromJSON`, `denormalizeFromXML`)

**What happens**

`getPropertyType()` reads the PHP reflection type-hint. Properties declared as
`public array $name = []` return the type string `'array'`. `isBuiltinType('array')` returns
`true`, so the denormalizer chain is **never called** for these properties. Every array property
(e.g. `Patient.name`, `Patient.identifier`, `Parameters.parameter`) is stored as a raw PHP
`array` of plain associative arrays instead of properly-typed FHIR objects.

**Concrete proof**

```php
$patient->name[0]
// actual:   ['use' => 'official', 'family' => 'Chalmers', 'given' => [...]]
// expected: HumanName { use: 'official', family: 'Chalmers', … }

$params->parameter[0]
// actual:   ['name' => ['@value' => 'string'], 'valueString' => ['@value' => 'string']]
// expected: ParametersParameter { name: StringPrimitive, value: StringPrimitive }
```

**Test failures caused**

- `Patient.name.ofType(HumanName)` → 0 results
- `Patient.name.as(HumanName).use` → fails
- `Parameters.parameter[0].value.is(FHIR.string)` → 0 results (key is `valueString`, not `value`;
  polymorphic scan only works on PHP objects, not raw arrays)
- All five `testTypeA*` tests

**Why round-trip serialization tests still pass**: `serialize(deserialize(json))` converts raw
arrays back to JSON correctly — the bug is invisible to output-comparison tests.

---

## Root Cause B — FHIRPath: resource class name includes `Resource` suffix

**Files affected**
- `src/Component/FHIRPath/src/Function/TypeFunction.php`
- `src/Component/FHIRPath/src/Type/FHIRTypeResolver.php` (`inferType`)

**What happens**

Generated resource classes are named `PatientResource`, `ObservationResource`, etc. Both
`TypeFunction::getTypeInfo()` and `FHIRTypeResolver::inferType()` fall back to extracting the
last segment of the class name, returning `'PatientResource'` instead of `'Patient'`. The
`#[FhirResource(type: 'Patient')]` PHP attribute is already present on every resource class and
is already used correctly in `FHIRPathEvaluator::matchesResourceType()` — it just isn't used in
the type-introspection path.

**Concrete proof**

```
Patient.type().name              → 'PatientResource'  (expected 'Patient')
Patient.is(Patient)              → false              (expected true)
Patient.ofType(FHIR.Patient)     → 0 results          (expected 1)
```

**Test failures caused**

`testType15`, `testType16`, `testType17`, `testType18`, `testType19`, `testType20`, `testType21`,
`testType22`, `testType23`, and all `ofType(SomeResource)` / `as(SomeResource)` expressions.

---

## Root Cause C — FHIRPath evaluator: FHIR type context discarded during primitive unwrapping

**Files affected**
- `src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php` (`wrapValue`, `normalizeValue`,
  `navigateProperty`)
- `src/Component/FHIRPath/src/Function/TypeFunction.php`
- `src/Component/FHIRPath/src/Type/FHIRTypeResolver.php` (`inferType`, `isOfType`)

**What happens (three sub-cases)**

**C1 — Scalar model properties**: `Patient.active` is declared as `?bool` directly on the model.
The FHIR type `'boolean'` lives in `#[FhirProperty(fhirType: 'boolean')]` on the property.
Once PHP `true` enters a `Collection` there is nothing to link it to `FHIR.boolean` vs
`System.Boolean`.

**C2 — Primitive wrapper classes**: `Patient.birthDate` is a `DatePrimitive`. `normalizeValue()`
finds `#[FHIRPrimitive]`, extracts `->value = '1974-12-25'` (PHP string), and the FHIR type
`'date'` is silently dropped.

**C3 — String-subtype primitives**: `UriPrimitive`, `UuidPrimitive`, `CodePrimitive` etc. all
unwrap to plain PHP `string`. After unwrapping, `inferType('urn:uuid:...')` returns `'string'`
instead of `'uuid'`.

**Fix**: Introduce a lightweight `TypedValue` carrier:

```php
final class TypedValue {
    public function __construct(
        public readonly mixed  $value,    // PHP scalar used for comparisons / arithmetic
        public readonly string $fhirType  // FHIR type name e.g. 'boolean', 'date', 'code'
    ) {}
}
```

- `wrapValue(DatePrimitive)` → reads `#[FHIRPrimitive(primitiveType: 'date')]` → returns
  `Collection::single(new TypedValue('1974-12-25', 'date'))` instead of bare string.
- `navigateProperty` for scalar properties → reads `#[FhirProperty(fhirType: 'boolean')]` →
  returns `Collection::single(new TypedValue(true, 'boolean'))`.
- Every site that consumes collection values for arithmetic / comparison already calls
  `normalizeValue()` — add a `TypedValue instanceof` unwrap there.
- `TypeFunction` and `inferType` check `TypedValue` first and use `$tv->fhirType`.

---

## Root Cause D — FHIRPath type resolver: `System.Boolean` and `FHIR.boolean` collapse to same token

**Files affected**
- `src/Component/FHIRPath/src/Type/FHIRTypeResolver.php` (`normalizeTypeName`, `isOfType`)
- `src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php` (`visitTypeExpression`)

**What happens**

`normalizeTypeName('System.Boolean')` maps to `'boolean'` via `SYSTEM_TYPE_MAP`.
`normalizeTypeName('FHIR.boolean')` strips prefix → also `'boolean'`. After normalisation they
are identical strings, so `is(System.Boolean)` and `is(FHIR.boolean)` always return the same
result. A second bug: `strcasecmp` in `isOfType` makes `is(Boolean)` (which should mean
`System.Boolean`) match FHIR booleans.

**Fix D2 (chosen approach) — qualified names throughout**

Keep names fully namespace-qualified after normalisation:

| Input | D2 output |
|---|---|
| `'System.Boolean'` | `'System.Boolean'` |
| `'FHIR.boolean'` | `'FHIR.boolean'` |
| `'boolean'` (lowercase bare) | `'FHIR.boolean'` |
| `'Boolean'` (uppercase bare, one of 8 System primitives) | `'System.Boolean'` |
| `'Patient'` (CamelCase FHIR complex) | `'FHIR.Patient'` |
| `'FHIR.Patient'` | `'FHIR.Patient'` |

`inferType` returns qualified names:
- PHP `bool` / `int` / `float` / `string` literals → `'System.Boolean'`, `'System.Integer'`, etc.
- `TypedValue($v, 'boolean')` → `'FHIR.boolean'`
- `PatientResource` object → `'FHIR.Patient'` (via `#[FhirResource]` attribute, Fix B)

`isOfType` does exact string comparison only — `strcasecmp` fallback removed.

**Dependency**: D2 requires Fix C (TypedValue) because without it PHP `bool` carries no namespace
context and `inferType` cannot distinguish FHIR.boolean from System.Boolean.

---

## Fix dependency graph

```
Fix A (Serialization: typed objects for array properties)
  └─ independent; highest test-count impact

Fix B (FHIRPath: resolve PatientResource → Patient via #[FhirResource])
  └─ independent; small surgical change

Fix C (FHIRPath: TypedValue carrier preserves FHIR type through unwrapping)
  └─ prerequisite for D2

Fix D2 (FHIRPath: fully-qualified names in normalizeTypeName / inferType / isOfType)
  └─ depends on C
```

---

## Implementation order

### Phase 1 (this PR) — A + B
Low-risk, independent, high impact on test counts.

1. **Fix A**: In `denormalizeFromJSON` and `denormalizeFromXML`, after the built-in-type check
   fails (or instead of it for `array`), consult `FHIR_PROPERTY_MAP` via reflection to resolve
   the element FHIR type and call the denormalizer per element.
2. **Fix B**: In `TypeFunction::getTypeInfo` and `FHIRTypeResolver::inferType`, extract the FHIR
   resource type from the `#[FhirResource(type: '...')]` attribute before falling back to
   class-name stripping. Share the same helper already used by
   `FHIRPathEvaluator::resolveResourceTypeFromAttribute`.

### Phase 2 (next PR) — C + D2
More invasive; introduces `TypedValue` and rewires the type-resolution pipeline.

1. **Fix C**: Add `TypedValue` class; update `wrapValue`, `navigateProperty`, `normalizeValue`,
   `TypeFunction`, `inferType`, `isOfType`.
2. **Fix D2**: Rewrite `normalizeTypeName` to produce qualified names; update `inferType` to
   return qualified names; replace `strcasecmp` in `isOfType` with exact match.

---

## Test commands

```bash
# Run the FHIRPath spec suite
composer test-ai-fhirpath-spec

# Run all suites
composer test-ai

# After Phase 1, expect improvements in:
#   testType15-23 (resource type resolution)
#   testTypeA* (Parameters value access)
#   ofType(HumanName), as(HumanName) tests
#   polymorphics group
```
