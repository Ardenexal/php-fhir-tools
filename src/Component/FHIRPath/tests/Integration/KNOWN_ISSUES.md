# Known Issues — Post-Implementation Report

Generated after implementing Plan 1 (typed model support) and Plan 2 (fhir-test-cases data provider).

## 1. FHIRPath Evaluator: Missing Functions (68 spec test errors)

These functions are referenced by the official test suite but throw `EvaluationException: Unknown function` because they haven't been implemented in `FunctionRegistry`.

| Function | Failing test count | Description |
|---|---|---|
| `lowBoundary()` | 23 | Calculates lower precision boundary of a decimal/date |
| `highBoundary()` | 20 | Calculates upper precision boundary |
| `matchesFull()` | 5 | Like `matches()` but anchored (full-string match) |
| `comparable()` | 3 | Checks if two values are comparable |
| `precision()` | 2 | Returns the precision of a quantity |
| `toDecimal()` | 1 | Converts value to decimal type |

All 68 are errors, not failures — they throw unhandled exceptions rather than producing wrong results. Fixing any of these requires adding a new function class to `src/Component/FHIRPath/src/Function/`.

---

## 2. FHIRPath Evaluator: Operator Precedence (2 spec test failures)

**`testPrecedence2`** — `1+2*3+4 = 11` expects `true` but the evaluator produces `false`. Multiplication is not being given higher precedence than addition. The parser's precedence climbing for arithmetic operators is incomplete.

**`testPrecedence4`** — `(1 | 1 is Integer).count()` expects `2` but returns `1`. The `is` operator inside a union expression is consuming the `1` before the union can include it. Precedence between `|` and `is` is wrong.

---

## 3. FHIRPath Evaluator: `matches()` Edge Cases (3 spec test failures)

**`testMatchesEmpty`**, **`testMatchesEmpty2`**, **`testMatchesEmpty3`** — expressions involving `{}.matches(...)` or `matches({})` return an incorrect result instead of an empty collection. The `matches()` function doesn't handle empty collection arguments per spec.

**`testMatchesSingleLineMode1`** — `'A\n\t\t\tB'.matches('A.*B')` expects `true`. The FHIRPath spec requires `.` in patterns to match newlines (DOTALL mode), but the current implementation doesn't enable `PREG_DOTALL` / `(?s)` by default.

**`testMatchesFullWithin*`** (5 additional tests in the errors category) — The `matches()` function is applying full-anchor matching when partial matching was expected, or vice-versa. Related to missing `matchesFull()` semantics bleeding into `matches()`.

---

## 4. FHIRPath Evaluator: `in` Operator Not Implemented (1 spec test failure)

**`testPrecedence5`** — `true and '0215' in ('0215' | '0216')` expects `true` but throws because the `in` operator handler in `visitBinaryOperator()` is an explicit stub:

```php
// src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php:207
TokenType::IN, TokenType::CONTAINS => throw new EvaluationException("Operator '{$node->getOperator()->value}' not yet fully implemented", ...)
```

The `contains` operator has the same issue.

---

## 5. FHIRPath Evaluator: `is` Operator Error Handling (1 spec test failure)

**`testPrecedence3`** — `1 > 2 is Boolean` is marked `invalid="semantic"` in the spec XML, meaning it should throw a `FHIRPathException`. The evaluator evaluates it successfully instead of rejecting it. The `is` operator currently has no type-safety validation.

---

## 6. `FHIRPathSpecificationTest`: 854 Skipped Tests Due to Deserialization Failures

The majority of spec tests involve loading a resource file (e.g. `patient-example.xml`) via `FHIRSerializationService::createDefault()` and passing the result to the evaluator. Almost all of these are currently skipped with messages like:

> Could not deserialize resource file patient-example.xml: ...

The two-phase `createDefault()` factory is correctly wired, but the FHIR normalizers themselves are complex and may not yet correctly handle round-tripping real FHIR XML/JSON files. This is the highest-impact item to investigate — unblocking deserialisation would convert most of the 854 skips into real test results.

---

## 7. `FunctionRegistry` Shared Static State

When the FHIRPath unit tests run in the same PHPUnit process before the integration tests, `testResourceTypePrefixWithWhereFunction` and `testResourceTypePrefixWithExistsFunction` fail with `Unknown function: where` / `Unknown function: exists`. Running the integration tests in isolation (or with `--testsuite=integration`) works correctly.

The root cause is that `FunctionRegistry::getInstance()` uses a static singleton that can enter a bad state if certain unit tests modify or reset it. Worth adding a `tearDown` guard or switching to a non-static instance.

---

## 8. `FHIRTypeResolver::resolveResourceType()` Now Returns `null` as Fallback

The old fallback was `return 'FHIR' . $resourceType;` (wrong namespace, but never `null`). The new fallback returns `null` when no Models class is found. Callers that previously relied on always getting a string back will now receive `null`. The serialisation code handles `null` gracefully, but any external callers of `FHIRTypeResolver` that don't check for `null` could behave differently.
