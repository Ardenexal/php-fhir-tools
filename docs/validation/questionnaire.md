---
description: Validate QuestionnaireResponse and derived Questionnaire resources.
icon: clipboard-question
---

# Questionnaire Validation

Questionnaire and QuestionnaireResponse validation is a distinct workflow with its own
validators, separate from the general `FHIRValidationService`. Run both and
merge the reports for complete coverage.

| Class | Responsibility |
|---|---|
| `FHIRQuestionnaireValidator` (implements `FHIRQuestionnaireValidatorInterface`) | Validates a `QuestionnaireResponse` against its source `Questionnaire` (R4, R4B, R5) |
| `FHIRDerivedQuestionnaireValidator` | Validates a derived `Questionnaire` against an explicit base `Questionnaire` |
| `FHIRDerivedQuestionnaireValidationService` | Wraps the derived validator, resolving the base automatically from `derivedFrom` |
| `FHIRQuestionnaireResolverInterface` / `InMemoryFHIRQuestionnaireResolver` | Resolves a base `Questionnaire` by canonical URL |

{% hint style="info" %}
`FHIRQuestionnaireValidator` takes no required constructor arguments. The `validate()`
method signature is `validate(object $questionnaire, object $response, bool $strictStatus = true)`.
It throws `\InvalidArgumentException` if either argument is not a supported resource type.
{% endhint %}

## Validating a QuestionnaireResponse

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;

$questionnaireValidator = new FHIRQuestionnaireValidator();

// Structural checks (cardinality, bindings, invariants)
$structuralReport = $service->validate($response);

// Conformance against the source Questionnaire (linkId, required, enableWhen, type)
$questionnaireReport = $questionnaireValidator->validate($questionnaire, $response);

// Merge for a unified view
$merged = new FHIRValidationReport([
    ...$structuralReport->violations,
    ...$questionnaireReport->violations,
]);
```

Pass `strictStatus: false` to skip the required-item check regardless of response status
(useful for drafts). When `true`, that check applies only to responses with status
`completed` or `amended`. The validator never resolves `QuestionnaireResponse.questionnaire`
canonical URLs — callers supply the source `Questionnaire` object. Violations carry
`FHIRQuestionnaireConstraint::class` in `constraintClass` so they can be distinguished
after merging.

## Implementation rules

| Rule | Severity |
|---|---|
| Response item `linkId` must exist in the source Questionnaire | `error` |
| Response items must sit at the position their `linkId` is declared at in the Questionnaire hierarchy | `error` |
| Required, enabled items must be answered when status is `completed`/`amended`, checked per parent instance | `error` |
| A required group needs at least one answered descendant question | `error` |
| Non-repeating items: at most one occurrence per parent and one answer | `error` |
| Answer value type must match the declared item type | `warning` |
| Items present while their `enableWhen` conditions are unsatisfied | `warning` |
| `enableWhen.question` must reference a known `linkId` | `warning` |

{% hint style="warning" %}
SDC extensions (`enableWhenExpression`, `answerExpression`, calculated expressions, regex
constraints) and R5 `answerConstraint` are not yet covered. `enableWhen` answers are looked
up response-globally — a documented approximation of the spec's nearest-occurrence
resolution that is exact whenever the referenced question occurs once.
{% endhint %}

## Conformance coverage

The validator is exercised against the official `fhir/fhir-test-cases` QuestionnaireResponse
corpus via `FHIRQuestionnaireConformanceTest` (run with `composer test-ai-questionnaire-spec`).
Of the 78 eligible R4 cross-resource cases:

* **41 are asserted** — error/warning counts match seeded expectations and the verdict agrees
  with the HL7 Java validator's error-presence (answer-type mismatches are reported at
  `warning` rather than `error`, by design).
* **36 are out of scope** and left incomplete (not silently passing) — they test rules this
  validator does not implement (answerOption/value-set membership, min/max, regex, Quantity
  units, Attachment constraints, Reference target types, SDC `enableWhenExpression`).
* **1 is skipped** — its supporting resource is not a Questionnaire.

The validator reports no false-positive errors across the corpus (its error count never
exceeds the reference validator's).

## Derived Questionnaires

A derived `Questionnaire` declares a base via `derivedFrom`. The rules applied depend on the
derivation type read from `_derivedFrom[0].extension[questionnaire-derivationType]`:

* `compliesWith` (default) — new `linkId`s are forbidden; type/required/repeats/answerOption
  and minOccurs/maxOccurs rules apply.
* `extends` — new `linkId`s are allowed; all other rules apply.
* `inspiredBy` — no structural constraints are checked.

{% hint style="warning" %}
The derivation type cannot be read from the PHP model: the deserializer does not merge FHIR
primitive extension arrays (`_derivedFrom`) into `CanonicalPrimitive->extension`. Callers must
extract it from raw JSON with the static helper
`FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson(array $decoded)` and pass it
explicitly. The default `'compliesWith'` is conservative.
{% endhint %}

{% tabs %}
{% tab title="With explicit base" %}
```php
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidator;

$validator = new FHIRDerivedQuestionnaireValidator();

$derivationType = FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson($decodedJson);

$report = $validator->validate($derived, $base, $derivationType);
```
{% endtab %}

{% tab title="With automatic resolution" %}
```php
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRQuestionnaireResolver;

$resolver = new InMemoryFHIRQuestionnaireResolver([$baseQuestionnaire]);

$service = new FHIRDerivedQuestionnaireValidationService(
    new FHIRDerivedQuestionnaireValidator(),
    $resolver,
);

// Reads derivedFrom[0], resolves the base, then delegates.
// Returns an empty report when derivedFrom is absent or the URL cannot be resolved.
$report = $service->validate($derived, derivationType: 'compliesWith');
```
{% endtab %}
{% endtabs %}

`InMemoryFHIRQuestionnaireResolver` indexes the supplied Questionnaires by their `url` at
construction (Questionnaires without a `url` are silently skipped) and matches the canonical
URL exactly — version suffixes are not stripped. Derived-questionnaire violations carry
`FHIRDerivedQuestionnaireConstraint::class` in `constraintClass`.

See [Validation Reports & Violation Codes](reports.md) for the report structure.
