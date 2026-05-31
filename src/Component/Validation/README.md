# FHIR Validation Component

Validates FHIR PHP model objects against constraints encoded as PHP 8 attributes
on generated model classes. Supports R4, R4B, and R5. Distributed as part of
`ardenexal/fhir-tools`.

---

## Quick Start

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;

// 1. Wire the validator (without Symfony DI)
$pathService   = new FHIRPathService();
$accessor      = PropertyAccess::createPropertyAccessor();
$msgRegistry   = new FHIRValidationMessageRegistry();
$termClient    = new NullFHIRTerminologyClient();   // swap for HttpFHIRTerminologyClient in production
$refResolver   = new NullFHIRReferenceResolver();   // swap for a Bundle-aware resolver

$defaultFactory = new ConstraintValidatorFactory();
$factory = new class(
    $pathService, $accessor, $msgRegistry, $termClient, $refResolver, $defaultFactory,
) implements ConstraintValidatorFactoryInterface {
    public function __construct(
        private readonly FHIRPathService $pathService,
        private readonly \Symfony\Component\PropertyAccess\PropertyAccessorInterface $accessor,
        private readonly FHIRValidationMessageRegistry $msgRegistry,
        private readonly \Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface $termClient,
        private readonly \Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface $refResolver,
        private readonly ConstraintValidatorFactory $default,
    ) {}

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        return match (true) {
            $constraint instanceof FHIRPathInvariant    => new FHIRPathInvariantValidator($this->pathService, $this->msgRegistry),
            $constraint instanceof FHIRValueSetBinding  => new FHIRValueSetBindingValidator($this->msgRegistry, ['Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'], $this->termClient),
            $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
            $constraint instanceof FHIRSliceConstraint  => new FHIRSliceConstraintValidator($this->accessor, new \Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher()),
            $constraint instanceof FHIRTargetProfile    => new FHIRTargetProfileValidator($this->refResolver, $this->msgRegistry),
            default                                     => $this->default->getInstance($constraint),
        };
    }
};

$validator = Validation::createValidatorBuilder()
    ->enableAttributeMapping()
    ->setConstraintValidatorFactory($factory)
    ->getValidator();

$service = new FHIRValidationService($validator, $pathService);

// 2. Validate a resource
$patient = new \Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource();
$report  = $service->validate($patient);

// 3. Read the report
if ($report->isValid()) {
    echo "Patient is valid\n";
} else {
    foreach ($report->errors() as $violation) {
        printf("[%s] %s: %s\n", $violation->severity, $violation->path, $violation->message);
    }
}
```

> **Symfony DI users:** Register `FHIRValidationService` as a service and inject
> `ValidatorInterface` (wired via `FHIRBundle`) — the bundle handles validator
> factory registration automatically.

---

## Architecture Overview

Validation is attribute-driven: FHIR constraints are encoded as PHP 8 attributes on
generated model classes during code generation (`fhir:generate`). At runtime,
`FHIRValidationService` calls Symfony Validator's `validate()` — which reads the
attributes and dispatches to the matching `ConstraintValidator` — then augments
the result with FHIR-specific checks that Symfony cannot express.

### Validator Map

| Attribute | Validator | FHIR concept enforced |
|---|---|---|
| `#[FHIRPathInvariant]` | `FHIRPathInvariantValidator` | FHIRPath constraint expressions (`constraint.expression`) |
| `#[FHIRValueSetBinding]` | `FHIRValueSetBindingValidator` | Terminology binding (required/extensible/preferred) |
| `#[FHIRProfileConstraint]` | `FHIRProfileConstraintValidator` | Profile-specific cardinality and value rules |
| `#[FHIRFixedValue]` | `FHIRFixedValueValidator` | `fixed[x]` element constraints |
| `#[FHIRPatternValue]` | `FHIRPatternValueValidator` | `pattern[x]` element constraints |
| `#[FHIRSliceConstraint]` | `FHIRSliceConstraintValidator` | Slice cardinality (closed/open/openAtEnd) |
| `#[FHIRTargetProfile]` | `FHIRTargetProfileValidator` | Reference target profile conformance |
| `#[FHIRQuantityRange]` | `FHIRQuantityRangeValidator` | `minValue`/`maxValue` on Quantity |
| `#[FHIRTemporalRange]` | `FHIRTemporalRangeValidator` | `minValue`/`maxValue` on date/dateTime/instant |

### Service-level checks (no Symfony attribute)

| Check | Where | What it enforces |
|---|---|---|
| Modifier extension walk | `FHIRValidationService::validateModifierExtensions()` | Unknown modifier extension URLs → ERROR |
| Extension context | `FHIRValidationService::validateExtensionContexts()` | Extension applied outside declared context → ERROR |
| Extension `contextInvariant` | `FHIRValidationService::validateExtensionContexts()` | FHIRPath invariant on the extension's use context |
| MustSupport collection | `FHIRValidationService::collectMustSupportInfo()` | Null/empty must-support properties → INFO (opt-in) |
| Obligation enforcement | `FHIRValidationService::collectObligationViolations()` | Actor-scoped populate obligations → ERROR/WARNING/INFO |

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
  ├─ collectMustSupportInfo()    (only when $includeMustSupportInfo=true)
  ├─ validateExtensionContexts() (always; recursive walk across full resource tree)
  ├─ validateModifierExtensions() (only when FHIRIGTypeRegistry is provided)
  └─ collectObligationViolations() (only when FHIRObligationContext is provided)
       └─ applyNoErrorSuppression() (suppress errors for SHALL:no-error obligations)
```

---

## FHIR Specification Compatibility Matrix

The FHIR validation spec ([R4](https://hl7.org/fhir/R4/validation.html) /
[R5](https://hl7.org/fhir/R5/validation.html)) defines eight mandatory validation
categories. Coverage as of the current release:

| Validation Category | R4 | R4B | R5 | Notes |
|---|---|---|---|---|
| **Cardinality** (min/max) | ✅ | ✅ | ✅ | Generated Symfony `#[NotBlank]`/`#[Count]` attributes |
| **Required bindings** | ✅ | ✅ | ✅ | `FHIRValueSetBindingValidator`; enum-based |
| **FHIRPath invariants** | ⚠️ | ⚠️ | ⚠️ | Supported; eval errors emit `fhir:eval-error` INFO (not ERROR) |
| **Fixed values** | ✅ | ✅ | ✅ | `FHIRFixedValueValidator` |
| **Pattern values** | ✅ | ✅ | ✅ | `FHIRPatternValueValidator` |
| **Slicing** (closed/open/openAtEnd) | ✅ | ✅ | ✅ | `FHIRSliceConstraintValidator` |
| **Profile constraints** (generated) | ✅ | ✅ | ✅ | `FHIRProfileConstraintValidator`; requires pre-generated models |
| **Profile constraints** (dynamic/runtime) | ❌ | ❌ | ❌ | Dynamic StructureDefinition loading not yet supported |
| **Extension contexts** (`type=element`) | ⚠️ | ⚠️ | ⚠️ | Recursive walk; bare-type and foreign-root contexts deferred (require type-hierarchy resolution) |
| **Extension contexts** (`type=fhirpath/extension`) | ❌ | ❌ | ❌ | Always permitted in v1 |
| **Modifier extensions** (unknown URL) | ✅ | ✅ | ✅ | Recursive walk via `FHIRIGTypeRegistry` |
| **Modifier element impact** | ⚠️ | ⚠️ | ⚠️ | `#[FHIRIsModifier]` marks properties; no active enforcement |
| **Extensible/preferred bindings** | ⚠️ | ⚠️ | ⚠️ | Requires `FHIRTerminologyClientInterface` implementation; `NullFHIRTerminologyClient` skips silently |
| **Target profile references** | ⚠️ | ⚠️ | ⚠️ | Requires `FHIRReferenceResolverInterface` implementation; `NullFHIRReferenceResolver` skips silently |
| **Quantity range** (`minValue`/`maxValue`) | ✅ | ✅ | ✅ | `FHIRQuantityRangeValidator` |
| **Temporal range** (date/dateTime/instant) | ✅ | ✅ | ✅ | `FHIRTemporalRangeValidator`; handles partial dates (YYYY, YYYY-MM) |
| **MustSupport** | ✅ | ✅ | ✅ | Opt-in via `$includeMustSupportInfo=true`; emits INFO violations |
| **Obligations** (populate) | ⚠️ | ⚠️ | ⚠️ | `SHALL`/`SHOULD:populate` enforced; filter evaluation deferred |
| **Obligations** (behaviour-only) | ℹ️ N/A | ℹ️ N/A | ℹ️ N/A | display/persist/handle cannot be checked from a resource instance |
| **Questionnaire validation** | ❌ | ❌ | ❌ | Separate `FHIRQuestionnaireValidator` (planned) |
| **`$validate` operation output** | ❌ | ❌ | ❌ | `OperationOutcome` adapter planned |
| **Narrative / XHTML** | ❌ | ❌ | ❌ | Not implemented |
| **Business rules** (auth, duplicates) | ℹ️ N/A | ℹ️ N/A | ℹ️ N/A | Outside library scope; requires server context |

---

## Known Limitations

**FHIRPath evaluation errors** produce an `fhir:eval-error` INFO violation rather than
an ERROR. A valid resource will never receive a false-positive ERROR from an unsupported
FHIRPath expression. If your FHIRPath engine covers all invariant expressions in your
StructureDefinitions, INFO violations of this kind will not appear.

**Extensible and preferred bindings** are only validated when a real
`FHIRTerminologyClientInterface` implementation is provided. The default
`NullFHIRTerminologyClient` returns `true` unconditionally, meaning invalid codes
against extensible/preferred value sets will not be detected. Wire
`HttpFHIRTerminologyClient` (or a custom implementation) to enable terminology checking.

**Extension context validation** walks all nested sub-elements recursively. Extensions
on BackboneElements and complex-type properties are context-checked. At sub-element
level, only `type=element` contexts whose dotted expression shares the root resource
type are evaluated — bare type-name contexts (e.g. `"HumanName"`) and foreign-root
paths require FHIR type-hierarchy resolution and are deferred.

**Extension context types `fhirpath` and `extension`** are not evaluated. Any extension
with a `type=fhirpath` or `type=extension` context is treated as permitted regardless
of the context expression. Only `type=element` contexts are enforced.

**Profile validation requires pre-generated models.** The validator reads constraints
from PHP 8 attributes generated by `fhir:generate`. Profiles not pre-generated into PHP
classes (i.e. runtime/dynamic StructureDefinitions) cannot be validated. All HL7 core
profiles for R4/R4B/R5 are pre-generated.

**Target profile reference validation** requires a `FHIRReferenceResolverInterface`
that can resolve `Reference` objects to their target PHP model. The default
`NullFHIRReferenceResolver` always returns `null`, causing target-profile checks to be
silently skipped. Wire a Bundle-scanning or registry-based resolver to enable this.

**Obligation filter evaluation** is deferred. `#[FHIRObligation]` entries with a
non-null `filter` (FHIRPath condition) are skipped; only unconditional obligations are
enforced.

**`#[FHIRIsModifier]`** marks properties as modifier elements for introspection but has
no active validation enforcement. Consumer applications must check modifier element
values themselves.

**Questionnaire validation** is implemented by a separate `FHIRQuestionnaireValidator`
service, not by `FHIRValidationService`. See the Questionnaire Validation section below.

**`$validate` operation** — `FHIRValidationService` returns a `FHIRValidationReport`,
not an `OperationOutcome`. An `FHIRValidationReportMapper` producing
standards-compliant `OperationOutcomeResource` objects is planned.

---

## Configuration Guide

### FHIRTerminologyClientInterface — terminology server

Controls validation of extensible and preferred bindings.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;

// Production: call a real terminology server
$terminologyClient = new HttpFHIRTerminologyClient(
    baseUrl: 'https://tx.fhir.org/r4',
    httpClient: $psr18HttpClient,
);

// Development / offline: skip extensible/preferred checks entirely
$terminologyClient = new NullFHIRTerminologyClient();
```

Pass `$terminologyClient` to `FHIRValueSetBindingValidator` in the constraint
validator factory (see Quick Start). When using `NullFHIRTerminologyClient`, no
violation is raised for invalid codes against extensible or preferred value sets.

---

### FHIRReferenceResolverInterface — in-process reference resolution

Controls whether `#[FHIRTargetProfile]` constraints are enforced on `Reference`
properties.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;

// Null: silently skip all target-profile checks (default)
$resolver = new NullFHIRReferenceResolver();

// Custom: resolve from a Bundle's contained resources
$resolver = new class($bundle) implements FHIRReferenceResolverInterface {
    public function __construct(private readonly object $bundle) {}

    public function resolve(object $reference): ?object
    {
        $ref = (string) ($reference->reference ?? '');
        if (!str_starts_with($ref, '#')) {
            return null; // only local contained references
        }
        $id = ltrim($ref, '#');
        foreach ($this->bundle->contained ?? [] as $resource) {
            if (($resource->id ?? null) === $id) {
                return $resource;
            }
        }
        return null;
    }
};
```

---

### FHIRObligationContext — actor-scoped obligation enforcement

Obligation validation is opt-in and requires an actor URL. Omit it to skip all
obligation checks.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;

// Enforce obligations declared for a specific actor (e.g. a sender role)
$context = new FHIRObligationContext(
    actorUrl: 'http://hl7.org/fhir/uv/ips/ActorDefinition/Creator',
);

$report = $service->validate(
    $resource,
    profileUrls: ['http://hl7.org/fhir/uv/ips/StructureDefinition/Patient-uv-ips'],
    obligationContext: $context,
);
```

Obligations without a declared actor match every context. Obligations with a specific
actor only fire when `$context->actorUrl` matches. Pass `new FHIRObligationContext(null)`
to match only actor-less obligations.

---

### Profile validation

Pass profile canonical URLs to `validate()` to activate profile-group constraints:

```php
$report = $service->validate(
    $patient,
    profileUrls: [
        'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient',
    ],
);
```

Only profiles pre-generated into PHP classes (via `fhir:generate`) are supported.
Passing an unknown profile URL is silently ignored — no violation, no error.

---

### MustSupport reporting

Pass `$includeMustSupportInfo = true` to emit INFO violations for null/empty
must-support properties. Useful for implementation guide conformance reporting;
must-support is not a validity constraint per the FHIR spec.

```php
$report = $service->validate($patient, includeMustSupportInfo: true);

foreach ($report->info() as $info) {
    echo $info->message . "\n"; // "Must-support property 'identifier' is not populated."
}
```

---

## Validation Report

`FHIRValidationService::validate()` returns a `FHIRValidationReport`.

### FHIRValidationReport

| Method | Returns | Description |
|---|---|---|
| `isValid()` | `bool` | `true` when there are no error-severity violations |
| `hasErrors()` | `bool` | `true` when at least one error violation exists |
| `hasWarnings()` | `bool` | `true` when at least one warning violation exists |
| `errors()` | `list<FHIRValidationViolation>` | All error-severity violations |
| `warnings()` | `list<FHIRValidationViolation>` | All warning-severity violations |
| `info()` | `list<FHIRValidationViolation>` | All info-severity violations |
| `violations` | `list<FHIRValidationViolation>` | All violations (all severities) |

`isValid()` returns `true` when there are zero ERROR violations. Warnings and INFO
violations do not affect validity per the FHIR specification.

### FHIRValidationViolation

| Property | Type | Description |
|---|---|---|
| `severity` | `string` | `'error'` \| `'warning'` \| `'info'` |
| `path` | `string` | FHIR property path, e.g. `identifier[0].system` |
| `message` | `string` | Human-readable rendered message |
| `constraintClass` | `string` | FQCN of the Symfony constraint that fired |
| `profileGroup` | `?string` | Profile canonical URL if from a profile group, else `null` |
| `invariantKey` | `?string` | FHIRPath invariant key (e.g. `obs-7`), else `null` |
| `parameters` | `array` | Raw message template parameters |

### FHIRViolationCode

Violation codes are set on Symfony constraint violations and used to derive severity:

| Constant | Value | Severity mapped to |
|---|---|---|
| `FHIRViolationCode::ERROR` | `'fhir:error'` | `error` |
| `FHIRViolationCode::WARNING` | `'fhir:warning'` | `warning` |
| `FHIRViolationCode::INFO` | `'fhir:info'` | `info` |

Built-in Symfony constraint codes (from `#[NotBlank]`, `#[Count]`, etc.) map to
`error` by default.

---

## Questionnaire Validation

Questionnaire/QuestionnaireResponse validation is handled by a separate
`FHIRQuestionnaireValidator` service (planned — see GitHub #74). When available,
use it alongside `FHIRValidationService` for complete QuestionnaireResponse coverage:

```php
// Validate the response structure (cardinality, bindings, invariants)
$structuralReport = $service->validate($response);

// Validate against the source Questionnaire (linkId, required, enableWhen, type)
$questionnaireReport = $questionnaireValidator->validate($questionnaire, $response);

// Combine reports for a unified view
$allViolations = array_merge(
    $structuralReport->violations,
    $questionnaireReport->violations,
);
```

---

## OperationOutcome Mapping

An `FHIRValidationReportMapper` producing standards-compliant
`OperationOutcomeResource` objects for FHIR `$validate` operation responses is
planned (see GitHub #73). When available it will map violations as follows:

| `FHIRValidationViolation::$severity` | `OperationOutcomeIssue::$severity` | `OperationOutcomeIssue::$code` |
|---|---|---|
| `error` | `error` | `invariant` (FHIRPath), `value` (binding), `invalid` (default) |
| `warning` | `warning` | same mapping |
| `info` | `information` | `processing` (eval-error), `informational` (must-support) |

The `$validate` operation endpoint format per spec:
```
POST [base]/[ResourceType]/$validate?profile=[profile-url]
```

The operation always returns HTTP 200 OK when validation ran successfully, regardless
of findings. Structural errors (unparseable JSON/XML) may produce 4xx responses before
this library is reached.

`mode=delete` is not supported by this library — referential integrity checks require
a FHIR server context.

---

## References

- [FHIR R4 validation spec](https://hl7.org/fhir/R4/validation.html)
- [FHIR R5 validation spec](https://hl7.org/fhir/R5/validation.html)
- [FHIR R4 conformance rules](https://hl7.org/fhir/R4/conformance-rules.html)
- [FHIR $validate operation (R5)](https://hl7.org/fhir/R5/resource-operation-validate.html)
- [SDC Questionnaire validation](https://build.fhir.org/ig/HL7/sdc/en/validation.html)
- [ADR-002: Validator component location](./../../../.goat-flow/decisions/ADR-002-validator-component-location.md)
- [ADR-004: Extensible/preferred binding strictness](./../../../.goat-flow/decisions/ADR-004-extensible-preferred-binding-strictness.md)
- [ADR-005: Target profile resolver pattern](./../../../.goat-flow/decisions/ADR-005-targetprofile-resolver-pattern.md)
- [GitHub Epic #76](https://github.com/Ardenexal/php-fhir-tools/issues/76)
