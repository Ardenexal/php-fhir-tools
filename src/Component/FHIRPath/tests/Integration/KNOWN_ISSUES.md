# Known Issues — Outstanding Items

**Current Test Status: 948 tests, 633 passing (66.8%), 85 errors, 186 failures, 44 skipped**

This document tracks **unresolved** issues only. Completed work has been removed.

---

## 1. FHIRPath Evaluator: Missing Functions

These functions are referenced by the official test suite but throw `EvaluationException: Unknown function` or `SyntaxException` because they haven't been implemented in `FunctionRegistry`.

| Function | Failing test count | Description |
|---|---|---|
| `encode()` | 4 | Encodes a string (URL encoding) |
| `decode()` | 4 | Decodes a string (URL decoding) |
| `join()` | 3 | Joins a collection of strings with a separator |
| `escape()` | 2 | Escapes special characters in a string |
| `unescape()` | 2 | Unescapes special characters in a string |
| `toDecimal()` | 1 | Converts value to decimal type |
| `comparable()` | ? | Checks if two values are comparable |
| `precision()` | ? | Returns the precision of a quantity |

All errors throw unhandled exceptions rather than producing wrong results. Fixing these requires adding new function classes to `src/Component/FHIRPath/src/Function/`.

---

## 2. FHIRPath Evaluator: Comment Syntax Not Supported (7 spec test errors)

**Issue**: The FHIRPath parser does not support single-line (`//`) or multi-line (`/* */`) comments as specified in the FHIRPath grammar. Tests involving comments fail with `SyntaxException: Expected expression but found DIVIDE(/)` because `//` is being interpreted as two division operators.

**Affected tests**: `testComment1` through `testComment7`

**Fix required**: Add comment tokenization to the lexer and comment-aware parsing to the parser. Comments should be stripped during tokenization before parsing begins.

---

## 3. FHIRPath Evaluator: `type()` Function FHIR Type Detection

**Issue**: The `type()` function returns System types instead of FHIR types when called on primitive values extracted from FHIR resources.

**Example**:
- `Patient.active.type().namespace` returns `'System'` instead of `'FHIR'`
- `Patient.active.type().name` returns `'Boolean'` instead of `'boolean'`

**Root cause**: When FHIR resources are deserialized and properties accessed, FHIR primitive wrappers are automatically unwrapped to PHP scalars (e.g., `boolean` → `bool`, `string` → `string`), losing FHIR type metadata. This is correct per FHIRPath specification (property access should return primitive values), but means the `type()` function cannot distinguish between System primitives and FHIR primitives.

**Status**: This is a known trade-off. The current implementation correctly unwraps primitives for all other operations (comparison, arithmetic, function calls), which is required by the FHIRPath spec. Fixing `type()` to return FHIR types would require maintaining type metadata alongside values, which could impact performance and complicate other operations.

**Impact**: Low — Most real-world FHIRPath expressions use primitive values for comparison/calculation rather than type introspection.

---

## 4. FHIRPath Evaluator: Quantity Support — RESOLVED ✅

**Status**: ✅ All quantity features implemented and working

**What's working:**
- ✅ Quantity literals with UCUM units in single quotes: `185 '[lb_av]'`, `4 'g'`, `1 'wk'`
- ✅ Calendar duration literals with unquoted keywords: `7 days`, `1 week`, `1 month`
- ✅ Quantity extraction from FHIR resources (via `ComparisonService::extractQuantity()`)
- ✅ Quantity comparison with unit conversion: `4 'g' = 4000 'mg'`, `7 days = 1 'wk'`
- ✅ Equivalence operator (~) with relative tolerance: `4 'g' ~ 4040 'mg'` (1% difference, within 10% tolerance)
- ✅ Quantity arithmetic operations:
  - Multiplication: `2.0 'cm' * 2.0 'm' = 0.040 'm2'`
  - Division: `4.0 'g' / 2.0 'm' = 2 'g/m'`, `1.0 'm' / 1.0 'm' = 1 '1'`
- ✅ Calendar keyword to UCUM code mapping: `days`→`d`, `week`→`wk`, `month`→`mo`, etc.
- ✅ toString() preserves integer formatting: `1 'wk'` not `1.0 'wk'`
- ✅ toString() outputs calendar keywords without quotes: `1 week` not `1 'week'`

**Implementation details:**
- Lexer recognizes calendar duration keywords after numbers and normalizes to quantity tokens
- ToQuantityFunction parses both quoted UCUM codes and unquoted calendar keywords
- ComparisonService maps calendar keywords to UCUM codes for comparison (e.g., `days`→`d`, `wk`→`wk`)
- Equivalence operator (~) uses 10% relative tolerance per FHIRPath spec
- Arithmetic operations preserve original units in results (e.g., `'g' / 'm'` → `'g/m'`)

**Tests passing**: All 14 quantity tests in the spec suite now pass

---

## 5. FHIRPath Evaluator: Semantic Validation (3 spec test failures)

**`testPrecedence1`** — `-1.convertsToInteger()` is marked `invalid="semantic"` in the spec XML. The evaluator successfully evaluates it (returns `-1`) instead of throwing a `FHIRPathException`. Without parentheses, the expression is ambiguous about whether the unary minus applies to the literal or to the result of the function call. Valid: `(-1).convertsToInteger()`. Invalid: `-1.convertsToInteger()`.

**`testPrecedence3`** — `1 > 2 is Boolean` is marked `invalid="semantic"` in the spec XML, meaning it should throw a `FHIRPathException`. The evaluator evaluates it successfully (returns `true`) instead of rejecting it. The `is` operator is fully implemented but lacks semantic validation to detect ambiguous operator combinations. Per the FHIRPath spec, `is` cannot be applied to the result of a comparison operator without explicit parentheses. Valid: `(1 > 2) is Boolean`. Invalid: `1 > 2 is Boolean`.

**`testPrecedence6`** — Complex expression involving `in` operator within nested `exists()` calls returns `false` instead of expected `true`. Expression: `category.exists(coding.exists(system = '...' and code.trace('c') in ('vital-signs' | 'vital-signs2').trace('codes')))`. The `in` operator works in simple cases (testPrecedence5 passes) but may have issues with complex nested contexts or when combined with `trace()` function output.

---

## 6. `FunctionRegistry` Shared Static State

When the FHIRPath unit tests run in the same PHPUnit process before the integration tests, `testResourceTypePrefixWithWhereFunction` and `testResourceTypePrefixWithExistsFunction` fail with `Unknown function: where` / `Unknown function: exists`. Running the integration tests in isolation (or with `--testsuite=integration`) works correctly.

The root cause is that `FunctionRegistry::getInstance()` uses a static singleton that can enter a bad state if certain unit tests modify or reset it. Worth adding a `tearDown` guard or switching to a non-static instance.

---

## 7. `FHIRTypeResolver::resolveResourceType()` Now Returns `null` as Fallback

The old fallback was `return 'FHIR' . $resourceType;` (wrong namespace, but never `null`). The new fallback returns `null` when no Models class is found. Callers that previously relied on always getting a string back will now receive `null`. The serialisation code handles `null` gracefully, but any external callers of `FHIRTypeResolver` that don't check for `null` could behave differently.

---

## Summary of Remaining Issues (Priority Order)

### High Priority
1. **Missing Functions** (#1) — Affects ~24 tests
   - Priority: `encode()`/`decode()` (8 tests), `join()` (3 tests), `escape()`/`unescape()` (4 tests)

### Medium Priority
2. **Comment Syntax Support** (#2) — Affects 7 tests; requires lexer/parser enhancements
3. **`type()` Function FHIR Type Detection** (#3) — Low impact; known trade-off with primitive unwrapping

### Low Priority
4. **Semantic Validation** (#5) — Affects 3 tests; edge case validation for ambiguous expressions
5. **Function Registry State** (#6) — Test isolation issue; doesn't affect spec tests
6. **Type Resolver Null Check** (#7) — Backward compatibility concern; no current test failures

### Resolved ✅
- **Quantity Comparisons** (#4) — All quantity features implemented (14 tests passing)

