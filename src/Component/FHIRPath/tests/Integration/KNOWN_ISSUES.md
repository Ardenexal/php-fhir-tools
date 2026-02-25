# Known Issues — Outstanding Items

**Last Updated:** February 25, 2026  
**Current Test Status:** 948 tests, 642 passing (67.7%), 67 errors, 189 failures, 44 skipped

This document tracks **unresolved** issues only. Completed work has been removed.

---

## 1. FHIRPath Evaluator: `type()` Function FHIR Type Detection

**Issue**: The `type()` function returns System types instead of FHIR types when called on primitive values extracted from FHIR resources.

**Example**:
- `Patient.active.type().namespace` returns `'System'` instead of `'FHIR'`
- `Patient.active.type().name` returns `'Boolean'` instead of `'boolean'`

**Root cause**: When FHIR resources are deserialized and properties accessed, FHIR primitive wrappers are automatically unwrapped to PHP scalars (e.g., `boolean` → `bool`, `string` → `string`), losing FHIR type metadata. This is correct per FHIRPath specification (property access should return primitive values), but means the `type()` function cannot distinguish between System primitives and FHIR primitives.

**Status**: This is a known trade-off. The current implementation correctly unwraps primitives for all other operations (comparison, arithmetic, function calls), which is required by the FHIRPath spec. Fixing `type()` to return FHIR types would require maintaining type metadata alongside values, which could impact performance and complicate other operations.

**Impact**: Low — Most real-world FHIRPath expressions use primitive values for comparison/calculation rather than type introspection.

---

## 2. FHIRPath Evaluator: Semantic Validation (3 spec test failures)

**`testPrecedence1`** — `-1.convertsToInteger()` is marked `invalid="semantic"` in the spec XML. The evaluator successfully evaluates it (returns `-1`) instead of throwing a `FHIRPathException`. Without parentheses, the expression is ambiguous about whether the unary minus applies to the literal or to the result of the function call. Valid: `(-1).convertsToInteger()`. Invalid: `-1.convertsToInteger()`.

**`testPrecedence3`** — `1 > 2 is Boolean` is marked `invalid="semantic"` in the spec XML, meaning it should throw a `FHIRPathException`. The evaluator evaluates it successfully (returns `true`) instead of rejecting it. The `is` operator is fully implemented but lacks semantic validation to detect ambiguous operator combinations. Per the FHIRPath spec, `is` cannot be applied to the result of a comparison operator without explicit parentheses. Valid: `(1 > 2) is Boolean`. Invalid: `1 > 2 is Boolean`.

**`testPrecedence6`** — Complex expression involving `in` operator within nested `exists()` calls returns `false` instead of expected `true`. Expression: `category.exists(coding.exists(system = '...' and code.trace('c') in ('vital-signs' | 'vital-signs2').trace('codes')))`. The `in` operator works in simple cases (testPrecedence5 passes) but may have issues with complex nested contexts or when combined with `trace()` function output.

---

## 3. `FunctionRegistry` Shared Static State

When the FHIRPath unit tests run in the same PHPUnit process before the integration tests, `testResourceTypePrefixWithWhereFunction` and `testResourceTypePrefixWithExistsFunction` fail with `Unknown function: where` / `Unknown function: exists`. Running the integration tests in isolation (or with `--testsuite=integration`) works correctly.

The root cause is that `FunctionRegistry::getInstance()` uses a static singleton that can enter a bad state if certain unit tests modify or reset it. Worth adding a `tearDown` guard or switching to a non-static instance.

---

## 4. `FHIRTypeResolver::resolveResourceType()` Now Returns `null` as Fallback

The old fallback was `return 'FHIR' . $resourceType;` (wrong namespace, but never `null`). The new fallback returns `null` when no Models class is found. Callers that previously relied on always getting a string back will now receive `null`. The serialisation code handles `null` gracefully, but any external callers of `FHIRTypeResolver` that don't check for `null` could behave differently.

---

## Summary of Remaining Issues (Priority Order)

### High Priority
_(No high priority issues remaining)_

### Medium Priority
1. **`type()` Function FHIR Type Detection** (#1) — Low impact; known trade-off with primitive unwrapping
2. **Semantic Validation** (#2) — Affects 3 tests; edge case validation for ambiguous expressions

### Low Priority
3. **Function Registry State** (#3) — Test isolation issue; doesn't affect spec tests
4. **Type Resolver Null Check** (#4) — Backward compatibility concern; no current test failures

