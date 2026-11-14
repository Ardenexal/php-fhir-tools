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
| Answer must satisfy item constraint extensions: `minValue`/`maxValue`, `minLength`/`maxLength`, `maxDecimalPlaces`, `regex`, `minOccurs`/`maxOccurs` | `error` |
| `answerOption` membership, including the exclusive-option (`optionExclusive`) rule; an R5 `answerConstraint` of `optionsOrType` or `optionsOrString` also admits any value of the item type or any string | `error` |
| Non-answerable item types (`display`, abstract `question`) must not carry an answer | `error` |
| `Reference` answers must match the constrained target resource type and be a well-formed URL | `error` |
| `Attachment` answers must satisfy content-type / size / data-size constraints | `error` |
| `Quantity` answers must satisfy `minQuantity`/`maxQuantity` bounds and unit-option constraints | `error` |
| Answer value type must match the declared item type (an R5 `optionsOrString` item also accepts a string) | `warning` |
| A read-only item's answers must equal the result of its SDC `calculatedExpression` (see below) | `error` |
| Items present while their `enableWhen` conditions are unsatisfied | `warning` |
| `enableWhen.question` must reference a known `linkId` | `warning` |

{% hint style="warning" %}
SDC `answerExpression` is not yet covered. (`enableWhenExpression` — both the canonical SDC
and Kanta PHR variants — `calculatedExpression`, `regex` constraints and R5 `answerConstraint`
are now validated.) `enableWhen` answers are
looked up response-globally — a documented approximation of the spec's nearest-occurrence
resolution that is exact whenever the referenced question occurs once.
{% endhint %}

### Calculated answers

An enabled item with `readOnly: true` and an SDC `calculatedExpression` must carry exactly the
values its expression returns, in any order. Items inside a disabled group are not checked. The
FHIRPath expression is evaluated with the response item as its focus and `%context`, and with
`%resource` bound to the QuestionnaireResponse. `variable`
extensions on the Questionnaire, on each enclosing item and on the item itself are bound first,
in that order, as `%name` constants; a variable on a repeating group is evaluated against the
group instance that contains the answer.

Only read-only items are checked: SDC lets a user override the calculated value of an editable
item. The check is skipped, not reported, when the expression or a variable is not
`text/fhirpath`, calls `now()`, `today()` or `timeOfDay()` (the result depends on when it
runs), fails to evaluate, or a variable returns more than one value; when the
expression returns nothing; and when the calculated and answered values are different kinds,
such as a number against a `Coding`. Numeric values are compared as numbers, so `5`, `5.0` and a
string answer `'5'` are equal. Numbers are compared exactly, so a decimal calculation
should round to the precision the form stores (`(%weight / %height.power(2)).round(1)`).

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
