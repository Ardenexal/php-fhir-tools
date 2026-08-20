---
category: conformance-corpus
last_reviewed: 2026-06-04
---

# Lessons: FHIR Conformance Corpus

## Lesson: A corpus case name does not reliably indicate the rule under test

**Status:** active | **Created:** 2026-06-04 | **Evidence:** OBSERVED

The M14 plan assumed `time-qr` was a primitive-format case because the case name (`time`)
and its sibling `url-value` suggested "validate the primitive value's format". The source
Questionnaire (`vendor/fhir/fhir-test-cases/validator/questionnaire/brianpos/time-q.json`)
actually carries an `answerOption` list — so the real rule is **answerOption membership**
(answer `09:00:00` ∉ `{10:00:00, 11:00:00}`), not format. The case was reclassified from
M14.4 to M14.1 mid-implementation.

Similarly, `reference-invalid-resource-type-q` carries **no** constraint extension (the rule
is type-validity via the `ResourceType` enum), while the allow-list extension
(`questionnaire-referenceResource`) lives on the differently-named
`reference-unconstrained-resource-type-q`.

**Rule:** Before classifying a conformance case by its name, read the source artifact's actual
constraint carriers (item `extension` URLs, `answerOption`, core properties) and — where
available — the reference outcome counts in `manifest.json` (`dotnet-brianpos`
`fatal/error/warningCount`). Ground the rule in the data, not the filename. Note the corpus
ships no per-case reference *messages* — only counts — so error *identity* must be confirmed
by running the validator and inspecting which answer/path is flagged, not by the count alone.

**Evidence:** `FHIRQuestionnaireValidator::checkValueDomainRules()` (search: `checkValueDomainRules`);
M14 milestone Assumptions block (corrected in-place). Relates to [[model-object-initialization]]
(same validator, typed-property `isset` guard).
