---
category: extension-context-or-semantics
last_reviewed: 2026-06-02
---

# Footguns: Extension Context OR Semantics

## Footgun: A restrictive-looking fhirpath context does not restrict when a sibling element context OR-permits

**Status:** active | **Created:** 2026-06-02 | **Evidence:** OBSERVED (M10)

`#[FHIRExtensionContext]` attributes use OR semantics: the extension is permitted if ANY
declared context permits (`FHIRValidationService.php`, search: `classifyExtensionContexts`
— first `CONTEXT_PERMIT` short-circuits). A `type=fhirpath` context that reads like a
restriction therefore restricts nothing when a sibling `type=element` context already
matches the bearing element.

Real example: `ArtifactIsOwnedExtension` (`src/Component/Models/src/R4/Extension/ArtifactIsOwnedExtension.php`)
declares `type: 'fhirpath', expression: 'type.exists() and type = \'composed-of\''` AND
`type: 'element', expression: 'RelatedArtifact'`. On a RelatedArtifact whose `type` is
`depends-on`, the fhirpath arm confidently classifies DENY — but the element context
PERMITs, so full `validate()` correctly emits **zero** violations. This is spec-correct
(`StructureDefinition.context[]` entries are alternatives), not a bug.

Trap for test authors and reviewers: you cannot demonstrate a fhirpath (or any single
context) denial through `validate()` using a real generated extension that also carries a
matching element context. Either isolate the context on a dedicated fixture (see
`FhirpathBooleanContextExtensionFixture` in `src/Component/Validation/tests/Unit/Fixture/`)
or assert at the classify level via reflection on `classifyFhirpathContext`.
