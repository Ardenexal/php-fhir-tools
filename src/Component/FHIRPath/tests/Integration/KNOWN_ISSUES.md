# Known Issues — Outstanding Items

**Last Updated:** February 25, 2026  
**Current Test Status:** 948 tests, 652 passing (68.8%), 67 errors, 185 failures, 44 skipped

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

## 2. `FunctionRegistry` Shared Static State

When the FHIRPath unit tests run in the same PHPUnit process before the integration tests, `testResourceTypePrefixWithWhereFunction` and `testResourceTypePrefixWithExistsFunction` fail with `Unknown function: where` / `Unknown function: exists`. Running the integration tests in isolation (or with `--testsuite=integration`) works correctly.

The root cause is that `FunctionRegistry::getInstance()` uses a static singleton that can enter a bad state if certain unit tests modify or reset it. Worth adding a `tearDown` guard or switching to a non-static instance.

---

## 3. `FHIRTypeResolver::resolveResourceType()` Now Returns `null` as Fallback

The old fallback was `return 'FHIR' . $resourceType;` (wrong namespace, but never `null`). The new fallback returns `null` when no Models class is found. Callers that previously relied on always getting a string back will now receive `null`. The serialisation code handles `null` gracefully, but any external callers of `FHIRTypeResolver` that don't check for `null` could behave differently.

---

## Summary of Remaining Issues (Priority Order)

### High Priority
_(No high priority issues remaining)_

### Medium Priority
1. **`type()` Function FHIR Type Detection** (#1) — Low impact; known trade-off with primitive unwrapping

### Low Priority
2. **Function Registry State** (#2) — Test isolation issue; doesn't affect spec tests
3. **Type Resolver Null Check** (#3) — Backward compatibility concern; no current test failures

