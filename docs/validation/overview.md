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
* [Runtime Profiles](runtime-profiles.md) — validate against StructureDefinitions you load, without generating classes.
* [Questionnaire Validation](questionnaire.md) — a separate validator for questionnaires and responses.
* [The $validate Operation](operation-outcome.md) — producing an `OperationOutcome`.
* [Configuration](configuration.md) — wiring terminology clients, resolvers, and obligation contexts.
* [Validation Reports & Violation Codes](reports.md) — report structure and severity codes.

## Quick start

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;

// In Symfony, inject FHIRValidationServiceInterface instead: FHIRBundle wires it
$service = FHIRValidationServiceBuilder::create()->build();

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
The builder takes a terminology client, a reference resolver, an IG type registry and
StructureDefinitions to validate against at runtime; see [Runtime Profiles](runtime-profiles.md).
Wiring the constraint-validator factory by hand is covered on the [Configuration](configuration.md)
page. Symfony DI users get all of this from `FHIRBundle`.
{% endhint %}

`FHIRValidationService::validate()` has the following signature:

```php
public function validate(
    object $resource,
    array $profileUrls = [],
    bool $includeMustSupportInfo = false,
    ?FHIRObligationContext $obligationContext = null,
    bool $deriveProfilesFromClass = false,
): FHIRValidationReport
```

`validateWithOptions($resource, new FHIRValidationOptions(...))` takes the same settings plus
`profilesFromMeta`, which checks each resource against the profiles in its own `meta.profile`. See
[Runtime Profiles](runtime-profiles.md#validate).

## Architecture

Validation is attribute-driven. FHIR constraints are emitted as PHP 8 attributes on the
generated model classes during code generation (`fhir:generate`). At runtime,
`FHIRValidationService` calls Symfony Validator's `validate()` — which reads the attributes and
dispatches each to its matching `ConstraintValidator` — then augments the result with
FHIR-specific checks that Symfony cannot express: extension contexts and structure, narrative
XHTML, primitive formats, `Coding.system`, input the reader could not place, Bundle `fullUrl`
rules, modifier extensions, must-support, and obligations.

### Validator map

| Attribute | Validator | FHIR concept enforced | Page |
|---|---|---|---|
| `#[FHIRPathInvariant]` | `FHIRPathInvariantValidator` | FHIRPath constraint expressions | [Invariants](invariants.md) |
| `#[FHIRValueSetBinding]` | `FHIRValueSetBindingValidator` | Terminology bindings | [Terminology](terminology.md) |
| `#[FHIRProfileConstraint]` | `FHIRProfileConstraintValidator` | Profile cardinality/value rules | [Structural](structural.md) |
| `#[FHIRProfileInvariant]` | `FHIRProfileInvariantValidator` | A profile's FHIRPath invariants | [Invariants](invariants.md) |
| `#[FHIRProfileBinding]` | `FHIRProfileBindingValidator` | A profile's required and extensible bindings | [Terminology](terminology.md) |
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
| Extension structure | `validateExtensionStructure()` | Missing `url`, non-absolute top-level `url`, or a value type the definition does not allow → ERROR; the extension's own invariants and bindings at their usual severity |
| Complex extension sub-extensions | `ComplexExtensionChecker` | Missing required or undeclared sub-extension → ERROR |
| Narrative XHTML | `NarrativeXhtmlChecker` | `txt-1` / `txt-2` on every `xhtml` value, with the offending element, attribute or entity named → ERROR |
| Primitive formats | `PrimitiveFormatChecker` | Unparsable temporal and decimal lexemes; malformed `code`, `id`, `oid`, `uri`, `base64Binary` → ERROR |
| `Coding.system` | `CodingSystemChecker` | A relative `system`, or one that names a ValueSet → ERROR |
| Unknown input | `UnknownInputChecker` | Elements the deserializer read but could not place on the model → ERROR |
| Bundle `fullUrl` | `BundleEntryFullUrlChecker` | `Bundle.entry.fullUrl` that is not an absolute URL → ERROR |
| Bundle reference resolution | `BundleReferenceResolutionChecker` | Relative reference to an entry the bundle holds that the `fullUrl` rules cannot resolve → ERROR |
| Modifier extension walk | `validateModifierExtensions()` | Unknown modifier extension URLs → ERROR (needs registry) |
| Obligation enforcement | `collectObligationViolations()` | Actor-scoped populate obligations → ERROR/WARNING/INFO |

The extension, modifier, must-support and obligation checks are documented under
[Extensions, Modifiers & Obligations](extensions.md).

Every check from narrative XHTML down to Bundle reference resolution runs on every call and needs
no configuration. The unknown-input check, the missing-sub-extension check and the unparsable
temporal check report what the deserializer recorded, so they only find something on a resource
read from JSON or XML, not on one built in PHP.

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
  ├─ runtime profile pass        (profile URLs no generated class declares, resolved by a
  │                               RuntimeProfileResolver; and meta.profile with profilesFromMeta)
  │
  ├─ collectMustSupportInfo()    (only when $includeMustSupportInfo = true)
  ├─ validateExtensionContexts() (always; recursive walk across the full resource tree)
  ├─ NarrativeXhtmlChecker, PrimitiveFormatChecker, CodingSystemChecker,
  │  UnknownInputChecker, ComplexExtensionChecker,
  │  BundleEntryFullUrlChecker, BundleReferenceResolutionChecker  (always)
  ├─ validateExtensionStructure() (always)
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
| Slicing (closed/open/openAtEnd) | Supported | `FHIRSliceConstraintValidator`; `value`, `pattern` and `exists` discriminators. Closed slicing the validator cannot match is enforced as open |
| Profile constraints (generated) | Supported | `FHIRProfileConstraintValidator`; requires pre-generated models |
| Profile constraints (dynamic/runtime) | Supported | `RuntimeProfileResolver`; StructureDefinitions must carry a snapshot; see [Runtime Profiles](runtime-profiles.md) |
| Extension contexts (element/fhirpath/extension) | Supported | Recursive walk; defer-not-deny safety; see [Extensions](extensions.md) |
| Extension structure and sub-extensions | Supported | `url`, value type, missing and undeclared sub-extensions; generated or runtime definitions |
| Modifier extensions (unknown URL) | Supported | Recursive walk; requires a `FHIRIGTypeRegistry` |
| Extensible / preferred bindings | Conditional | Needs a real terminology client; otherwise `fhir:unchecked-binding` INFO |
| Target profile references | Conditional | Needs a `FHIRReferenceResolverInterface`; null resolver skips silently |
| Quantity range | Supported | `FHIRQuantityRangeValidator` |
| Temporal range | Supported | `FHIRTemporalRangeValidator`; handles partial dates (YYYY, YYYY-MM) |
| MustSupport | Supported | Opt-in via `$includeMustSupportInfo = true`; INFO violations |
| Obligations (populate) | Conditional | `SHALL`/`SHOULD:populate` enforced; filter evaluation deferred |
| `$validate` operation output | Supported | `validateForOperation()` → `OperationOutcomeResource` |
| Questionnaire validation | Separate service | `FHIRQuestionnaireValidator`; see [Questionnaire](questionnaire.md) |
| Narrative / XHTML | Partial | `NarrativeXhtmlChecker`: `txt-1` / `txt-2`, DOCTYPE, undefined entities, disallowed elements and attributes. The XHTML namespace, link schemes and stray text are not checked |
| Primitive formats | Partial | `PrimitiveFormatChecker`. Malformed `boolean` and `integer` values are lost when read, so they cannot be reported |
| Unknown elements | Partial | `UnknownInputChecker`; deserialized input only. XML element order is not checked |
| Bundle `fullUrl` and references | Partial | Absolute `fullUrl` and relative-reference resolution. Missing `fullUrl`, RESTful `fullUrl` mismatch and document reachability are not checked |

### Known limitations

* **FHIRPath evaluation errors** produce an `fhir:eval-error` INFO violation, never a false
  ERROR. A valid resource is never failed by an unsupported FHIRPath expression.
* **Extensible / preferred bindings** are only checked when a real
  `FHIRTerminologyClientInterface` is wired. Without one, each skipped check emits a
  `fhir:unchecked-binding` INFO violation (queryable via
  `FHIRValidationReport::hasUncheckedBindings()`). See [Terminology](terminology.md).
* **Runtime profiles need snapshots.** A profile you have not generated can be validated from its
  StructureDefinition, but only when it carries a snapshot, and rules inside slices are skipped. All
  HL7 core profiles for R4/R4B/R5 are pre-generated. See [Runtime Profiles](runtime-profiles.md#limits).
* **Slice discriminators** of type `type` or `profile`, `$this`, and paths through `resolve()` are
  not evaluated. Closed or openAtEnd slicing that depends on one is enforced as open, and the
  generator (or the runtime compiler) reports a warning. See [Structural](structural.md#slicing).
* **Narrative XHTML** is checked for `txt-1` / `txt-2`, but not for the XHTML namespace: the XML
  reader drops namespace bindings on `div`, so a correct narrative and a wrong one look the same.
* **Target profile reference validation** requires a `FHIRReferenceResolverInterface`. The
  default `NullFHIRReferenceResolver` returns `null`, silently skipping the check. See
  [References](references.md).
* **Obligation filter evaluation** is deferred: obligations with a non-null FHIRPath `filter`
  are skipped; only unconditional obligations fire.
* **`#[FHIRIsModifier]`** marks modifier properties for introspection only — no active
  enforcement; consumers must check modifier element values themselves.
* **Questionnaire validation** is a separate `FHIRQuestionnaireValidator` service, not part of
  `FHIRValidationService`. See [Questionnaire](questionnaire.md).
