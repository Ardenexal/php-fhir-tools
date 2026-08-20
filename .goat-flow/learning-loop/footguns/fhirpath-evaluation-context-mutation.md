---
category: fhirpath-evaluation-context-mutation
last_reviewed: 2026-07-11
---

# Footguns: FHIRPath EvaluationContext mutation

## Footgun: `FHIRPathEvaluator::evaluate()` mutates the passed context, so `getRootResource()` is not stable across calls on a shared context

**Status:** active | **Created:** 2026-07-11 | **Evidence:** OBSERVED (M05 SDC extract)

`FHIRPathEvaluator::evaluate()` (`src/Component/FHIRPath/src/Evaluator/FHIRPathEvaluator.php`,
search: `setRootResource`) mutates the `EvaluationContext` it receives via **setters** —
`setRootResource($focus)`, `setCurrentNode($focus)`, `setVariable('this', …)` — not the immutable
`with*` copy pattern. So the focus you pass as the second arg **overwrites** `rootResource` on that
context object. If a caller reuses one `EvaluationContext` across many `evaluate()` calls with
different focus nodes, `$context->getRootResource()` returns whatever focus was passed *last*, not the
original resource.

The only thing that saves the common path: `FHIRPathService::evaluate` **clones** the context via
`withFhirVersion(...)` *before* handing it to the evaluator — but **only when `$fhirVersion` is
non-null**. Pass `fhirVersion = null` and there is no clone; the caller's own context is mutated in
place.

Trap for callers that need a *stable* handle to the containing resource (e.g. SDC `$extract`
evaluating each `definitionExtractValue` with a QR *item* as focus while `%resource` must stay the QR
root): do **not** read the root back from `getRootResource()` between calls. Instead bind it once to
the dedicated `%resource` slot — `EvaluationContext::withResourceNode()` / the `resourceNode`
constructor arg — which `evaluate()` never mutates, and read it via `getResourceNode()`. See
`FHIRQuestionnaireResponseExtractService` (search: `resourceNode`) and M05
(`.goat-flow/plans/sdc-extract/M05-fhirpath-focus-context.md`).

Related: `%context` resolves to `getRootResource()` (the focus, post-mutation), while
`%resource`/`%rootResource` resolve to `getResourceNode() ?? getRootResource()`.
