---
description: Validate FHIR resources against the specification and Implementation Guides.
icon: shield-check
---

# Overview & Architecture

The Validation component checks FHIR PHP model objects against constraints encoded as PHP 8
attributes on the generated model classes: structural rules, FHIRPath invariants, terminology
bindings, profiles, extension contexts, ranges, and obligations. It supports R4, R4B, and R5,
and produces a structured [validation report](reports.md).

## In this section

* [Structural & Profile Validation](structural.md) — cardinality, slices, fixed and pattern values.
* [FHIRPath Invariant Validation](invariants.md) — constraint expressions such as `obs-7`.
* [Terminology & Binding Validation](terminology.md) — codes against ValueSet bindings.
* [Reference & Target Profile Validation](references.md) — what a `Reference` may point to.
* [Quantity & Temporal Range Validation](ranges.md) — `minValue` / `maxValue` bounds.
* [Extensions, Modifiers & Obligations](extensions.md) — context, modifier, must-support, and obligation checks.
* [Questionnaire Validation](questionnaire.md) — a separate validator for questionnaires and responses.
* [The $validate Operation](operation-outcome.md) — producing an `OperationOutcome`.
* [Configuration](configuration.md) — wiring terminology clients, resolvers, and obligation contexts.
* [Validation Reports & Violation Codes](reports.md) — report structure and severity codes.

## Quick start

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;

// $validator is a Symfony ValidatorInterface wired with the FHIR constraint validators.
// In Symfony DI this is handled by FHIRBundle; for manual wiring see Configuration.
$service = new FHIRValidationService($validator, $pathService);

$report = $service->validate(new PatientResource());

if ($report->isValid()) {
    echo "Patient is valid\n";
} else {
    foreach ($report->errors() as $violation) {
        printf("[%s] %s: %s\n", $violation->severity, $violation->path, $violation->message);
    }
}
```

{% hint style="info" %}
Manual wiring of the constraint-validator factory, terminology client, reference resolver, and
type-hierarchy resolver is covered on the [Configuration](configuration.md) page. Symfony DI
users get this automatically via `FHIRBundle`.
{% endhint %}

`FHIRValidationService::validate()` has the following signature:

```php
public function validate(
    object $resource,
    array $profileUrls = [],
    bool $includeMustSupportInfo = false,
    ?FHIRObligationContext $obligationContext = null,
): FHIRValidationReport
```

## Architecture

Validation is attribute-driven. FHIR constraints are emitted as PHP 8 attributes on the
generated model classes during code generation (`fhir:generate`). At runtime,
`FHIRValidationService` calls Symfony Validator's `validate()` — which reads the attributes and
dispatches each to its matching `ConstraintValidator` — then augments the result with
FHIR-specific checks that Symfony cannot express (extension contexts, modifier extensions,
must-support, obligations).

### Validator map

| Attribute | Validator | FHIR concept enforced | Page |
|---|---|---|---|
| `#[FHIRPathInvariant]` | `FHIRPathInvariantValidator` | FHIRPath constraint expressions | [Invariants](invariants.md) |
| `#[FHIRValueSetBinding]` | `FHIRValueSetBindingValidator` | Terminology bindings | [Terminology](terminology.md) |
| `#[FHIRProfileConstraint]` | `FHIRProfileConstraintValidator` | Profile cardinality/value rules | [Structural](structural.md) |
| `#[FHIRFixedValue]` | `FHIRFixedValueValidator` | `fixed[x]` element constraints | [Structural](structural.md) |
| `#[FHIRPatternValue]` | `FHIRPatternValueValidator` | `pattern[x]` element constraints | [Structural](structural.md) |
| `#[FHIRSliceConstraint]` | `FHIRSliceConstraintValidator` | Slice cardinality | [Structural](structural.md) |
| `#[FHIRTargetProfile]` | `FHIRTargetProfileValidator` | Reference target profiles | [References](references.md) |
| `#[FHIRQuantityRange]` | `FHIRQuantityRangeValidator` | `minValue`/`maxValue` on Quantity | [Ranges](ranges.md) |
| `#[FHIRTemporalRange]` | `FHIRTemporalRangeValidator` | `minValue`/`maxValue` on date/time | [Ranges](ranges.md) |

### Service-level checks (no Symfony attribute)

| Check | Method | What it enforces |
|---|---|---|
| Must-support collection | `collectMustSupportInfo()` | Null/empty must-support properties → INFO (opt-in) |
| Extension context | `validateExtensionContexts()` | Extension applied outside its declared context → ERROR |
| Extension `contextInvariant` | `validateExtensionContexts()` | FHIRPath invariant on the extension's use context |
| Modifier extension walk | `validateModifierExtensions()` | Unknown modifier extension URLs → ERROR (needs registry) |
| Obligation enforcement | `collectObligationViolations()` | Actor-scoped populate obligations → ERROR/WARNING/INFO |

These are documented under [Extensions, Modifiers & Obligations](extensions.md).

### Validation flow

```
FHIRValidationService::validate($resource, $profileUrls, $includeMustSupportInfo, $obligationContext)
  │
  ├─ Symfony Validator::validate($resource, groups=['Default', ...$profileUrls])
  │    └─ reads #[FHIRPathInvariant], #[FHIRValueSetBinding], #[FHIRProfileConstraint],
  │       #[FHIRFixedValue], #[FHIRPatternValue], #[FHIRSliceConstraint],
  │       #[FHIRTargetProfile], #[FHIRQuantityRange], #[FHIRTemporalRange],
  │       and built-in Symfony constraints (#[NotBlank], #[Count], etc.)
  │
  ├─ collectMustSupportInfo()    (only when $includeMustSupportInfo = true)
  ├─ validateExtensionContexts() (always; recursive walk across the full resource tree)
  ├─ validateModifierExtensions() (only when a FHIRIGTypeRegistry is provided)
  └─ collectObligationViolations() (only when a FHIRObligationContext is provided)
       └─ applyNoErrorSuppression() (suppress errors for SHALL:no-error obligations)
```

## Compatibility & limitations

The FHIR validation spec ([R4](https://hl7.org/fhir/R4/validation.html) /
[R5](https://hl7.org/fhir/R5/validation.html)) defines several mandatory validation categories.
Coverage as of the current release (applies to R4, R4B, and R5 unless noted):

| Validation category | Status | Notes |
|---|---|---|
| Cardinality (min/max) | Supported | Generated Symfony `#[NotBlank]` / `#[Count]` attributes |
| Required bindings | Supported | `FHIRValueSetBindingValidator`; enum-backed |
| FHIRPath invariants | Supported | Engine eval errors emit `fhir:eval-error` INFO (not ERROR) |
| Fixed / pattern values | Supported | `FHIRFixedValueValidator` / `FHIRPatternValueValidator` |
| Slicing (closed/open/openAtEnd) | Supported | `FHIRSliceConstraintValidator` |
| Profile constraints (generated) | Supported | `FHIRProfileConstraintValidator`; requires pre-generated models |
| Profile constraints (dynamic/runtime) | Not supported | Dynamic StructureDefinition loading not yet supported |
| Extension contexts (element/fhirpath/extension) | Supported | Recursive walk; defer-not-deny safety; see [Extensions](extensions.md) |
| Modifier extensions (unknown URL) | Supported | Recursive walk; requires a `FHIRIGTypeRegistry` |
| Extensible / preferred bindings | Conditional | Needs a real terminology client; otherwise `fhir:unchecked-binding` INFO |
| Target profile references | Conditional | Needs a `FHIRReferenceResolverInterface`; null resolver skips silently |
| Quantity range | Supported | `FHIRQuantityRangeValidator` |
| Temporal range | Supported | `FHIRTemporalRangeValidator`; handles partial dates (YYYY, YYYY-MM) |
| MustSupport | Supported | Opt-in via `$includeMustSupportInfo = true`; INFO violations |
| Obligations (populate) | Conditional | `SHALL`/`SHOULD:populate` enforced; filter evaluation deferred |
| `$validate` operation output | Supported | `validateForOperation()` → `OperationOutcomeResource` |
| Questionnaire validation | Separate service | `FHIRQuestionnaireValidator`; see [Questionnaire](questionnaire.md) |
| Narrative / XHTML | Not implemented | — |

### Known limitations

* **FHIRPath evaluation errors** produce an `fhir:eval-error` INFO violation, never a false
  ERROR. A valid resource is never failed by an unsupported FHIRPath expression.
* **Extensible / preferred bindings** are only checked when a real
  `FHIRTerminologyClientInterface` is wired. Without one, each skipped check emits a
  `fhir:unchecked-binding` INFO violation (queryable via
  `FHIRValidationReport::hasUncheckedBindings()`). See [Terminology](terminology.md).
* **Profile validation requires pre-generated models.** Runtime/dynamic StructureDefinitions
  cannot be validated. All HL7 core profiles for R4/R4B/R5 are pre-generated.
* **Target profile reference validation** requires a `FHIRReferenceResolverInterface`. The
  default `NullFHIRReferenceResolver` returns `null`, silently skipping the check. See
  [References](references.md).
* **Obligation filter evaluation** is deferred: obligations with a non-null FHIRPath `filter`
  are skipped; only unconditional obligations fire.
* **`#[FHIRIsModifier]`** marks modifier properties for introspection only — no active
  enforcement; consumers must check modifier element values themselves.
* **Questionnaire validation** is a separate `FHIRQuestionnaireValidator` service, not part of
  `FHIRValidationService`. See [Questionnaire](questionnaire.md).
