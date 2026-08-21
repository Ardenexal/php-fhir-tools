---
category: conformance-corpus
last_reviewed: 2026-08-20
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

## Lesson: Comparing error counts measures agreement, not capability

**Status:** active | **Created:** 2026-08-20 | **Evidence:** ACTUAL_MEASURED
**Decision changed:** size conformance work from paired findings, never from the ABOVE/EQUAL/BELOW class.
**Trigger phase:** SCOPE

**What happened:** the corpus-parity plan was drafted against "168 `BELOW` cases", the count
`compare-java-outcomes.php` produces by comparing our error total with the reference validator's per
case. Pairing the two sides finding by finding gave **548**, and the two numbers do not stand in any
fixed relation, because the class distorts in both directions at once.

It **overstates**, because different wording reads as a missing check.
`outcomes/ardenexal/R4.Observation-ex-pain.json-base.json`: Java reports `Observation.code: minimum
required = 1` and `The property 'value' is invalid`; we report `This value should not be blank.` on
path `code`. Ours *is* Java's first finding. The case is one finding short, not two. 37 of the 168
already reported at least one error from us.

It also **understates**, because a case where both sides report one error about different things
classifies `EQUAL` and disappears. Only pairing sees those.

Three further distortions surfaced once pairing existed, each inflating the gap:

- The reference validator splits one unreadable document into several parse diagnostics
  (`json-no-quotes-2` gives three) against our single rejection. Counting them one-to-one scores
  checks as missing while we already reject the whole file.
- Manifest entries that state counts inline carry no issue texts, so those errors were counted but had
  no message to classify — the total quietly shrank by however many texts happened to be absent.
- One case can dominate. `japanese-utf8-ok` contributes 108 of R4's 410, all downstream of a single
  refusal to read a file whose encoding the reference validator tolerates.

**Prevention:** measure paired findings (`ComparisonReport::missingFindingCount()`), read the
concentration beside the total (`missingByCase()`), and treat the case classes as what the
specification suite asserts rather than as a work estimate. When building any cross-validator matcher,
make the uncertain direction *unmatched* — a missed pair overstates the gap and the next audit catches
it, while a false pair erases a finding nothing downstream can see. Audit by reading the pairings
claimed (`JavaFindingMatcher::matchedPairs()`), never the leftovers; every defect above was found that
way, and a containment rule that claimed zero pairings corpus-wide was deleted for being unauditable.

**Evidence:** `JavaFindingMatcher` (search: `precision over recall, deliberately`);
`MissingFindingMeasurementTest::testTheKnownDifferentlyWordedFindingIsNotCountedAsMissing`.
Relates to [[verification]] (asserting against our own output).

## Lesson: `issue.expression` is the only thing in a reference outcome that identifies the instance

**Status:** active | **Created:** 2026-08-20 | **Evidence:** ACTUAL_MEASURED
**Decision changed:** read `expression` (or `location`) when a comparison needs to know *which* element a reference finding is about; the message text cannot say.
**Trigger phase:** ACT

**What happened:** the reference validator names the element in a cardinality message by **type**, not by
instance: `List.status: minimum required = 1, but only found 0`. A document holding two Lists that both
lack `status` therefore produces two identical messages, and any comparison keyed on the message alone
pairs them in arrival order. The aggregate stays correct while the attribution is a coin toss, which is
undetectable afterwards.

`issue.expression` carries what the message does not. All 108 cardinality findings in the vendored corpus
have one, and for nested instances it is the full path with each step annotated by the type and id it
resolved to:
`Bundle.entry[1].resource/*MeasureReport/…*/.contained[0]/*Bundle/…*/.entry[0].resource/*List/…*/`. Strip
the `/*Type/id*/` annotations and the leading resource type and it is exactly our own root-relative path.
Older outcomes use `location` for the same thing.

**Prevention:** when plumbing it through, grow the text list and the expression list **once per error each**,
including when a field is absent. An early draft appended each under its own `is_string` guard, so one error
with a text and no expression would have shifted every later expression onto the wrong text — invisibly,
because both lists are padded to equal length afterwards.

**Evidence:** `JavaOutcomeReader::fromOperationOutcome()` (search: `expression` is FHIRPath into the instance);
`JavaFindingMatcher::matchesByCardinality()`; pinned by
`JavaFindingMatcherTest::testCardinalityPairingRefusesADifferentInstanceOfTheSameType`.
