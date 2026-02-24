# Known Issues — Post-Implementation Report

Generated after implementing Plan 1 (typed model support) and Plan 2 (fhir-test-cases data provider).

**Current Test Status: 948 tests, 521 passing (55.0%), 177 errors, 206 failures, 44 skipped**

---

## 1. FHIRPath Evaluator: Missing Functions (39 spec test errors)

These functions are referenced by the official test suite but throw `EvaluationException: Unknown function` or `SyntaxException` because they haven't been implemented in `FunctionRegistry`.

| Function | Failing test count | Description |
|---|---|---|
| `type()` | 12 | Returns the type of the input value |
| `sort()` | 10 | Sorts a collection |
| `encode()` | 4 | Encodes a string (URL encoding) |
| `decode()` | 4 | Decodes a string (URL decoding) |
| `join()` | 3 | Joins a collection of strings with a separator |
| `escape()` | 2 | Escapes special characters in a string |
| `unescape()` | 2 | Unescapes special characters in a string |
| `toDecimal()` | 1 | Converts value to decimal type |
| `comparable()` | ? | Checks if two values are comparable |
| `precision()` | ? | Returns the precision of a quantity |
| ~~`lowBoundary()`~~ | ~~11~~ | ~~Calculates lower precision boundary of a decimal/date~~ ✅ **IMPLEMENTED** |
| ~~`highBoundary()`~~ | ~~8~~ | ~~Calculates upper precision boundary~~ ✅ **IMPLEMENTED** |
| ~~`matchesFull()`~~ | ~~5~~ | ~~Like `matches()` but anchored (full-string match)~~ ✅ **IMPLEMENTED** |

All errors throw unhandled exceptions rather than producing wrong results. Fixing these requires adding new function classes to `src/Component/FHIRPath/src/Function/`.

---

## 2. FHIRPath Evaluator: Operator Precedence (0 spec test failures) ✅ RESOLVED

~~**`testPrecedence2`** — `1+2*3+4 = 11` expects `true` but the evaluator produces `false`. Multiplication is not being given higher precedence than addition. The parser's precedence climbing for arithmetic operators is incomplete.~~ **FIXED** — Parser precedence was already correct.

~~**`testPrecedence4`** — `(1 | 1 is Integer).count()` expects `2` but returns `1`. The `is` operator inside a union expression is consuming the `1` before the union can include it. Precedence between `|` and `is` is wrong.~~ **FIXED** — The parser precedence was correct (`1 | (1 is Integer)`), but `Collection::union()` used `array_unique()` with loose comparison, treating `1` (integer) and `true` (boolean) as equal. Fixed by implementing strict comparison (===) in the union method.

---

## 3. FHIRPath Evaluator: `matches()` Edge Cases (0 spec test failures) ✅ RESOLVED

~~**`testMatchesEmpty`**, **`testMatchesEmpty2`**, **`testMatchesEmpty3`** — expressions involving `{}.matches(...)` or `matches({})` return an incorrect result instead of an empty collection. The `matches()` function doesn't handle empty collection arguments per spec.~~ **FIXED** — Empty collection handling implemented in `MatchesFunction::execute()` (lines 28-30).

~~**`testMatchesSingleLineMode1`** — `'A\n\t\t\tB'.matches('A.*B')` expects `true`. The FHIRPath spec requires `.` in patterns to match newlines (DOTALL mode), but the current implementation doesn't enable `PREG_DOTALL` / `(?s)` by default.~~ **FIXED** — DOTALL mode (`/s` flag) is now automatically appended to all regex patterns (lines 55-67).

~~**`testMatchesFullWithin*`** (5 additional tests in the errors category) — The `matches()` function is applying full-anchor matching when partial matching was expected, or vice-versa. Related to missing `matchesFull()` semantics bleeding into `matches()`.~~ **FIXED** — `matchesFull()` function fully implemented with automatic anchoring (`^(?:...)$`) and proper DOTALL support. All 5 testMatchesFull tests passing.

---

## 4. FHIRPath Evaluator: `in` and `contains` Operators (0 spec test failures) ✅ RESOLVED

~~**`testPrecedence5`** — `true and '0215' in ('0215' | '0216')` expects `true` but throws because the `in` operator handler in `visitBinaryOperator()` is an explicit stub.~~ **FIXED** — Both `in` and `contains` operators fully implemented via `evaluateMembership()` method. testPrecedence5 passing.

---

## 5. FHIRPath Evaluator: `lowBoundary()` and `highBoundary()` Parser Issues (0 spec test errors) ✅ RESOLVED

~~**Issue**: Date/time literals followed by method calls like `.lowBoundary()` caused syntax errors because the lexer greedily consumed the `.` character as part of the literal, even when it should be a member access operator.~~

~~**Example failure**: `@T10:30.lowBoundary(9)` was lexed as `@T10:30.` (invalid TIME literal) followed by `lowBoundary`, causing "Expected end of expression but found IDENTIFIER" errors.~~

~~**Affected tests**: All `lowBoundary()` and `highBoundary()` tests involving datetime/time literals (approximately 19 tests total).~~

**FIXED** — The lexer now only includes `.` in datetime/time literals when followed by digits (for fractional seconds). Method calls on literals now parse correctly. Both functions are fully implemented with:
- Decimal precision boundary calculations
- Precision validation (0-31 per FHIRPath spec)  
- Full datetime/time literal support with precision formatting
- Support for `@`-prefixed literals and time-only literals (`@T...`)

**Test Results**: 27 of 28 `lowBoundary` tests now pass (1 failure is for Quantity literals which are not yet supported).

---

## 6. FHIRPath Evaluator: Comment Syntax Not Supported (7 spec test errors)

**Issue**: The FHIRPath parser does not support single-line (`//`) or multi-line (`/* */`) comments as specified in the FHIRPath grammar. Tests involving comments fail with `SyntaxException: Expected expression but found DIVIDE(/)` because `//` is being interpreted as two division operators.

**Affected tests**: `testComment1` through `testComment7`

**Fix required**: Add comment tokenization to the lexer and comment-aware parsing to the parser. Comments should be stripped during tokenization before parsing begins.

---

## 7. FHIRPath Evaluator: Primitive Value Extraction (62+ spec test failures)

**Issue**: When evaluating paths that should return primitive values (strings, codes, integers, etc.), the evaluator returns FHIR primitive objects (arrays with `'@value'` => actual_value) instead of extracting the primitive values themselves.

**Example failure**:
```
Expression: name.given
Expected: 'Peter' (string)
Actual: ['@value' => 'Peter'] (array)
```

**Impact**: Approximately 62 tests fail because assertions expect scalar values but receive arrays. This affects:
- Property access on FHIR resources (e.g., `name.given`, `telecom.use`)
- Comparisons involving primitive values
- Function calls expecting scalar inputs

**Fix required**: Implement primitive value unwrapping in the evaluator. When a FHIR primitive type is accessed, the evaluator should automatically extract the `@value` field to return the scalar value per FHIRPath specification.

**Location**: Likely in `FHIRPathEvaluator::visitMemberAccess()` or when processing typed model properties.

---

## 8. FHIRPath Evaluator: Collection Comparison (40+ spec test failures)

**Issue**: Collection equality operators (`=`, `!=`) are not correctly implementing FHIRPath collection comparison semantics. The spec requires:
- Collections are equal if they have the same elements in any order
- Empty collections in comparisons produce empty results (not true/false)
- Different numeric precisions make values incomparable (empty result)

**Affected test patterns**:
- `testEquality*` — Collection equality comparisons
- `testNEquality*` — Collection inequality comparisons
- Complex object comparisons (e.g., `name.take(2) = name.take(2).last()`)

**Examples**:
- `(1 | 2) = (1 | 2)` should return `true`
- `name != name` should return `false` (but currently returns empty or fails)
- `@2012-04-15 = @2012-04-15T10:00:00` should return empty (different precisions)

**Fix required**: Revise equality/inequality operators in `FHIRPathEvaluator::evaluateComparison()` to properly handle:
1. Collection-to-collection comparisons
2. Precision-aware date/time comparisons
3. Empty result vs. boolean result semantics

---

## 9. FHIRPath Evaluator: Date/Time Comparison with Precision (30+ spec test failures)

**Issue**: Date and time comparisons don't properly handle different precisions. Per FHIRPath spec:
- Values with different precisions are incomparable (return empty)
- `@2018-03 < @2018-03-01` should return empty (year-month vs. full date)
- `@2018-03-01T10:30 < @2018-03-01T10:30:00` should return empty (minute vs. second precision)
- Only when precisions match can comparison return true/false

**Affected tests**: `testLessThan23-27`, `testLessOrEqual23-25`, `testGreatorOrEqual23-25`, various equality tests

**Fix required**: Add precision tracking to date/time literals and implement precision-aware comparison logic.

---

## 10. FHIRPath Evaluator: Quantity Comparisons (10+ spec test failures)

**Issue**: Tests involving FHIR Quantity values fail because:
1. Quantity literal syntax (`185 '[lb_av]'`) may not be fully parsed
2. Quantity comparison operations are not implemented
3. Unit conversion for comparable units is not supported

**Affected tests**: Tests with expressions like `Observation.value < 200 '[lb_av]'`

**Fix required**: Implement Quantity type support in the evaluator including:
- Quantity literal parsing
- Quantity comparison operators
- Basic unit compatibility checking

---

## 11. FHIRPath Evaluator: Semantic Validation (3 spec test failures)

**`testPrecedence1`** — `-1.convertsToInteger()` is marked `invalid="semantic"` in the spec XML. The evaluator successfully evaluates it (returns `-1`) instead of throwing a `FHIRPathException`. Without parentheses, the expression is ambiguous about whether the unary minus applies to the literal or to the result of the function call. Valid: `(-1).convertsToInteger()`. Invalid: `-1.convertsToInteger()`.

**`testPrecedence3`** — `1 > 2 is Boolean` is marked `invalid="semantic"` in the spec XML, meaning it should throw a `FHIRPathException`. The evaluator evaluates it successfully (returns `true`) instead of rejecting it. The `is` operator is fully implemented but lacks semantic validation to detect ambiguous operator combinations. Per the FHIRPath spec, `is` cannot be applied to the result of a comparison operator without explicit parentheses. Valid: `(1 > 2) is Boolean`. Invalid: `1 > 2 is Boolean`.

**`testPrecedence6`** — Complex expression involving `in` operator within nested `exists()` calls returns `false` instead of expected `true`. Expression: `category.exists(coding.exists(system = '...' and code.trace('c') in ('vital-signs' | 'vital-signs2').trace('codes')))`. The `in` operator works in simple cases (testPrecedence5 passes) but may have issues with complex nested contexts or when combined with `trace()` function output.

---

## 12. `FHIRPathSpecificationTest`: 854 Skipped Tests Due to Deserialization Failures ✅ RESOLVED

~~The majority of spec tests involve loading a resource file (e.g. `patient-example.xml`) via `FHIRSerializationService::createDefault()` and passing the result to the evaluator. Almost all of these are currently skipped with messages like:~~

> ~~Could not deserialize resource file patient-example.xml: ...~~

~~The two-phase `createDefault()` factory is correctly wired, but the FHIR normalizers themselves are complex and may not yet correctly handle round-tripping real FHIR XML/JSON files. This is the highest-impact item to investigate — unblocking deserialisation would convert most of the 854 skips into real test results.~~

**FIXED** — The FHIR serialization service now correctly deserializes XML and JSON resource files. Test count improved from 94 tests (854 skipped) to **948 tests (39 skipped)**. The 39 remaining skips are for unsupported output types (e.g., `Quantity`) and missing input files, not deserialization failures. Deserialization skip logic has been removed from the test suite.

---

## 13. `FunctionRegistry` Shared Static State

When the FHIRPath unit tests run in the same PHPUnit process before the integration tests, `testResourceTypePrefixWithWhereFunction` and `testResourceTypePrefixWithExistsFunction` fail with `Unknown function: where` / `Unknown function: exists`. Running the integration tests in isolation (or with `--testsuite=integration`) works correctly.

The root cause is that `FunctionRegistry::getInstance()` uses a static singleton that can enter a bad state if certain unit tests modify or reset it. Worth adding a `tearDown` guard or switching to a non-static instance.

---

## 14. `FHIRTypeResolver::resolveResourceType()` Now Returns `null` as Fallback

The old fallback was `return 'FHIR' . $resourceType;` (wrong namespace, but never `null`). The new fallback returns `null` when no Models class is found. Callers that previously relied on always getting a string back will now receive `null`. The serialisation code handles `null` gracefully, but any external callers of `FHIRTypeResolver` that don't check for `null` could behave differently.

---

## Summary of Changes Needed (Priority Order)

### High Priority (Biggest Impact)
1. **Primitive Value Extraction** (#7) — Affects ~62 tests; core functionality blocking comparisons and assertions
2. **Collection Comparison** (#8) — Affects ~40 tests; fundamental operator semantics  
3. **Date/Time Precision Handling** (#9) — Affects ~30 tests; spec compliance for temporal comparisons

### Medium Priority
4. **Missing Functions** (#1) — Affects 39 tests; incremental implementation
   - Priority functions: `type()` (12 tests), `sort()` (10 tests)
5. **Comment Syntax Support** (#6) — Affects 7 tests; parser enhancement
6. **Quantity Comparisons** (#10) — Affects ~10 tests; advanced feature

### Low Priority
7. **Semantic Validation** (#11) — Affects 3 tests; edge case validation
8. **Function Registry State** (#13) — Test isolation issue; doesn't affect spec tests
9. **Type Resolver Null Check** (#14) — Backward compatibility; doesn't affect current tests

**Estimated impact**: Fixing issues #7, #8, and #9 would resolve approximately 132 failures, moving the passing rate from **55%** to approximately **69%**. Adding the high-priority functions (#1) would push it to approximately **73%**.
