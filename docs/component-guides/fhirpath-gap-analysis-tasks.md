# FHIRPath Gap Analysis — Task List

## Context

Analysis of the FHIRPath component against the [FHIRPath 2.0 spec](https://hl7.org/fhirpath/) and [FHIR R4 FHIRPath extensions](https://hl7.org/fhir/R4/fhirpath.html). The implementation currently has ~63 registered functions and a complete parser/evaluator for core expression types, but is missing ~40 spec-required functions across several categories.

---

## Status: Tracked as tasks #1–#42

Tasks were created in the session on 2026-02-23. Run `composer test:fhir-path` and `composer test-fhir` to verify progress.

**Progress (as of 2026-02-24):** Tasks #1–#32 complete. Tasks #33–#42 remain.

---

## Group 1: Core Existence & Collection Functions (spec §5.1–5.4)

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #1 | ✅ | `subsetOf` | `subsetOf(other: collection): Boolean` | Every item in input exists in `other` |
| #2 | ✅ | `supersetOf` | `supersetOf(other: collection): Boolean` | Every item in `other` exists in input |
| #3 | ✅ | `isDistinct` | `isDistinct(): Boolean` | Returns true if no duplicates |
| #4 | ✅ | `repeat` | `repeat(projection: expression): collection` | Repeatedly applies projection until stable |
| #5 | ✅ | `not` | `not(): Boolean` | Function form of `!` / logical NOT |

## Group 2: String Functions (spec §5.6)

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #6 | ✅ | `replaceMatches` | `replaceMatches(regex: String, substitution: String): String` | Regex-based replace (existing `replace()` is plain string only) |
| #7 | ✅ | `toChars` | `toChars(): collection` | Splits string into individual character collection |

## Group 3: Tree Navigation Functions (spec §5.8)

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #8 | ✅ | `children` | `children(): collection` | All direct child nodes of input items |
| #9 | ✅ | `descendants` | `descendants(): collection` | All descendant nodes (recursive children); depends on #8 |

## Group 4: Utility Functions (spec §5.9)

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #10 | ✅ | `trace` | `trace(name: String, projection?: expression): collection` | Logs diagnostics, returns input unchanged |

## Group 5: Aggregate Functions (spec §7, STU)

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #11 | ✅ | `aggregate` | `aggregate(aggregator: expression, init?: value): value` | Reduces collection to single value; exposes `$total` variable |

## Group 6: Type Conversion Functions (spec §5.5)

All `convertsTo*` functions check if the value *can* be converted without actually converting.

| Task | Status | Function | Notes |
|------|--------|----------|-------|
| #12 | ✅ | `toBoolean(): Boolean` | |
| #13 | ✅ | `convertsToBoolean(): Boolean` | Depends on #12 |
| #14 | ✅ | `toInteger(): Integer` | |
| #15 | ✅ | `convertsToInteger(): Boolean` | Depends on #14 |
| #16 | ✅ | `convertsToDecimal(): Boolean` | `toDecimal()` exists; companion missing |
| #17 | ✅ | `toDate(): Date` | |
| #18 | ✅ | `convertsToDate(): Boolean` | Depends on #17 |
| #19 | ✅ | `toDateTime(): DateTime` | |
| #20 | ✅ | `convertsToDateTime(): Boolean` | Depends on #19 |
| #21 | ✅ | `toTime(): Time` | |
| #22 | ✅ | `convertsToTime(): Boolean` | Depends on #21 |
| #23 | ✅ | `toQuantity([unit: String]): Quantity` | |
| #24 | ✅ | `convertsToQuantity([unit: String]): Boolean` | Depends on #23 |
| #25 | ✅ | `toString(): String` | |
| #26 | ✅ | `convertsToString(): Boolean` | Depends on #25 |

## Group 7: Type Namespace Support

| Task | Status | Feature | Notes |
|------|--------|---------|-------|
| #27 | ✅ | `System.` namespace | Type specifiers like `System.Boolean`, `System.Integer` etc. in `is`/`as`/`ofType()` |
| #28 | ✅ | `FHIR.` namespace | FHIR-qualified types like `FHIR.Patient`, `FHIR.string` etc. in type expressions |

## Group 8: FHIR R4-specific Functions

| Task | Status | Function | Signature | Notes |
|------|--------|----------|-----------|-------|
| #29 | ✅ | `extension` | `extension(url: String): collection` | Shortcut for `.extension.where(url = ...)` |
| #30 | ✅ | `getValue` | `getValue(): System.[type]` | Returns underlying system value for FHIR primitives |
| #31 | ✅ | `resolve` | `resolve(): collection` | Stub — delegates to context resolver callback or returns `{}` |
| #32 | ✅ | `memberOf` | `memberOf(valueset: String): Boolean` | Stub — requires terminology server |
| #33 | ✅ | `conformsTo` | `conformsTo(structure: String): Boolean` | Using callback pattern |
| #34 | ❌ | `subsumes` | `subsumes(code: Coding\|CodeableConcept): Boolean` | Stub — requires terminology server |
| #35 | ❌ | `subsumedBy` | `subsumedBy(code: Coding\|CodeableConcept): Boolean` | Stub — requires terminology server |
| #36 | ❌ | `htmlChecks` | `htmlChecks(): Boolean` | Validates xhtml using PHP `DOMDocument` |
| #37 | ❌ | `elementDefinition`, `slice`, `checkModifiers` | Various | Stubs; require StructureDefinition lookup service |

## Group 9: FHIR Environment Variables

| Task | Status | Variable | Notes |
|------|--------|----------|-------|
| #38 | ❌ | `%resource`, `%rootResource` | `rootResource` is stored internally but NOT exposed as external constants — `visitExternalConstant` won't find them |
| #39 | ❌ | Terminology variables | `%sct`, `%loinc`, `%"vs-[name]"`, `%"ext-[name]"` pre-defined URL constants — not pre-populated |

## Group 10: Behavioral / Semantic Gaps

| Task | Status | Feature | Notes |
|------|--------|---------|-------|
| #40 | ❌ | Equivalence `~` for Coding/CodeableConcept | FHIR R4 specifies `~` must ignore `id`/display and compare system+code only |
| #41 | ❌ | Date/time arithmetic | Adding/subtracting UCUM durations from Date/DateTime/Time values |
| #42 | ❌ | Quantity arithmetic & comparison | Same-unit comparison at minimum; full UCUM arithmetic as stretch goal |

---

## Critical Files

- `src/Component/FHIRPath/src/Function/FunctionRegistry.php` — register new functions here
- `src/Component/FHIRPath/src/Function/` — all function implementations live here
- `src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php` — evaluator changes for `children()`/`descendants()`, `$total` for `aggregate()`, operator extensions for Groups 9–10
- `src/Component/FHIRPath/src/Type/FHIRTypeResolver.php` — type namespace changes (Groups 7)
- `src/Component/FHIRPath/tests/Unit/Function/FunctionRegistryTest.php` — add new function registrations

## Priority Notes

- **High (spec compliance):** Groups 1–6 — core spec functions required for conformance — **ALL DONE**
- **Medium (type system):** Group 7 — `System.`/`FHIR.` namespaces appear in spec test cases — **ALL DONE**
- **Medium (FHIR-specific, no services):** Tasks #36–#39 — implementable without external services
- **Low / deferred:** Tasks #34–#35 (service-dependent stubs), Group 10 (significant evaluator work)
