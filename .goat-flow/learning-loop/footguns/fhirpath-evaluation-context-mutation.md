---
category: fhirpath-evaluation-context-mutation
last_reviewed: 2026-11-05
---

# Footguns: FHIRPath EvaluationContext mutation

## Footgun: `FHIRPathEvaluator::evaluate()` mutates the passed context, so `getRootResource()` is not stable across calls on a shared context

**Status:** active | **Created:** 2026-07-11 | **Evidence:** OBSERVED (M05 SDC extract)

`FHIRPathEvaluator::evaluate()` (`src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php`,
search: `setRootResource`) mutates the `EvaluationContext` it receives via **setters**, not the
immutable `with*` copy pattern. On every call it overwrites `currentNode` and the `this` variable. It
sets `rootResource` only while that is still null, so a context created without a root takes the focus
of its **first** call as the root and keeps it for every later call, even with a different focus.

Nothing clones the context for you: `FHIRPathService::evaluate` only derives a copy when passed a
`$fhirVersion` or `$strictMode`, so otherwise the caller's own object is mutated in place.

Trap for callers evaluating many focus nodes against one containing resource: bind the root explicitly
with `new EvaluationContext($root)`. Do not reuse a rootless context, and do not read the root back from
it expecting the latest focus.

Related: `%context` and `%resource` resolve to `getRootResource()`, while `%rootResource` resolves to
`getContainerResource() ?? getRootResource()`. Most `with*` methods rebuild the context field by field
and drop `containerResource`, so `%rootResource` can fall back to the root inside a function call (read
in the code, not exercised by a test).
