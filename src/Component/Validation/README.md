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

// Type-aware extension context resolution (FhirPropertyTypeHierarchyResolver) is on by default.
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
| **Extension contexts** (`type=element`) | ✅ | ✅ | ✅ | Recursive walk; path, bare-type, supertype (e.g. `DomainResource`/`Element`) and foreign-root type-path contexts resolved via `FhirPropertyTypeHierarchyResolver` (reflection, no codegen); deferred without a resolver |
| **Extension contexts** (`type=fhirpath`) | ✅ | ✅ | ✅ | Expression evaluated against the bearing element; denial only on a confident single-`false` result — empty results and engine errors defer (no violation) |
| **Extension contexts** (`type=extension`) | ✅ | ✅ | ✅ | Ancestor-extension URL chain assembled by descending into nested extensions; denial only on a fully-known chain (incl. the definitive empty chain at element level) that excludes the declared parent URL — unknown chains defer |
| **Modifier extensions** (unknown URL) | ✅ | ✅ | ✅ | Recursive walk via `FHIRIGTypeRegistry` |
| **Modifier element impact** | ⚠️ | ⚠️ | ⚠️ | `#[FHIRIsModifier]` marks properties; no active enforcement |
| **Extensible/preferred bindings** | ⚠️ | ⚠️ | ⚠️ | Requires a real `FHIRTerminologyClientInterface` implementation; without one (null or `NullFHIRTerminologyClient`) each skipped check emits a `fhir:unchecked-binding` INFO violation — see `FHIRValidationReport::hasUncheckedBindings()` |
| **Target profile references** | ⚠️ | ⚠️ | ⚠️ | Requires `FHIRReferenceResolverInterface` implementation; `NullFHIRReferenceResolver` skips silently |
| **Quantity range** (`minValue`/`maxValue`) | ✅ | ✅ | ✅ | `FHIRQuantityRangeValidator` |
| **Temporal range** (date/dateTime/instant) | ✅ | ✅ | ✅ | `FHIRTemporalRangeValidator`; handles partial dates (YYYY, YYYY-MM) |
| **MustSupport** | ✅ | ✅ | ✅ | Opt-in via `$includeMustSupportInfo=true`; emits INFO violations |
| **Obligations** (populate) | ⚠️ | ⚠️ | ⚠️ | `SHALL`/`SHOULD:populate` enforced; filter evaluation deferred |
| **Obligations** (behaviour-only) | ℹ️ N/A | ℹ️ N/A | ℹ️ N/A | display/persist/handle cannot be checked from a resource instance |
| **Questionnaire validation** | ❌ | ❌ | ❌ | Separate `FHIRQuestionnaireValidator` (planned) |
| **`$validate` operation output** | ✅ | ✅ | ✅ | `FHIRValidationService::validateForOperation()` |
| **Narrative / XHTML** | ❌ | ❌ | ❌ | Not implemented |
| **Business rules** (auth, duplicates) | ℹ️ N/A | ℹ️ N/A | ℹ️ N/A | Outside library scope; requires server context |

---

## Known Limitations

**FHIRPath evaluation errors** produce an `fhir:eval-error` INFO violation rather than
an ERROR. A valid resource will never receive a false-positive ERROR from an unsupported
FHIRPath expression. If your FHIRPath engine covers all invariant expressions in your
StructureDefinitions, INFO violations of this kind will not appear.

**Extensible and preferred bindings** are only validated when a real
`FHIRTerminologyClientInterface` implementation is provided. Without one — the client is
`null` or the default `NullFHIRTerminologyClient` — invalid codes against
extensible/preferred value sets will not be detected. The gap is not silent: each skipped
check emits a `fhir:unchecked-binding` INFO violation naming the unchecked value set, and
`FHIRValidationReport::hasUncheckedBindings()` reports it programmatically. These INFO
violations never affect `isValid()`. Wire `HttpFHIRTerminologyClient` (or a custom
implementation) to enable terminology checking and remove them.

**Extension context validation** walks all nested sub-elements recursively. Extensions
on BackboneElements and complex-type properties are context-checked. When
`FhirPropertyTypeHierarchyResolver` is wired, contexts are resolved by reflection over the
generated model class hierarchy (no codegen): bare-type contexts match the element's
resolved supertype set, so a supertype such as `"DomainResource"` or `"Element"` permits its
subtypes; foreign-root type-paths such as `"ElementDefinition.binding"` match when a path
segment is typed with the context root. Type resolution is monotonic — it only ever permits
more, never introduces a denial — so enabling the resolver cannot create a false positive.
Without a resolver, bare-type and foreign-root contexts are deferred (no violation emitted).

**Extension context type `fhirpath`** is evaluated against the element bearing the
extension. Denial requires confident evaluation: only an expression yielding a single
boolean `false` denies. An empty result (the engine cannot reliably resolve node-returning
shapes such as `ofType(...)`) or a FHIRPath evaluation error defers — no violation is
emitted, preserving the defer-not-deny safety property.

**Extension context type `extension`** is evaluated against the chain of enclosing
extension URLs. The walk descends into each extension's own sub-extensions, carrying the
ancestor URLs; the context permits when the declared parent URL appears anywhere in that
chain. Denial requires a fully-known chain — including the definitive empty chain of an
extension borne directly on an element — while a chain containing an unreadable URL
defers. Element-path contexts on extension-borne nested extensions defer on mismatch —
at nested sites confident denials come only from the `type=extension` arm or from a
`fhirpath` context evaluating to a single `false` against the parent extension (the
bearing element there) — and context invariants are not evaluated for nested extensions. Extensions carried by an extension's *value* element
are not walked. Note OR semantics across multiple contexts: a sibling context that
defers (e.g. a foreign-root element path) masks a confident `type=extension` denial.

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

**`$validate` operation** — use `FHIRValidationService::validateForOperation()` to
validate a resource and receive a standards-compliant `OperationOutcomeResource` directly.
`FHIRValidationService::validate()` remains available when a `FHIRValidationReport` is
preferred over an `OperationOutcome`.

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

// Development / offline: skip extensible/preferred checks
$terminologyClient = new NullFHIRTerminologyClient();
```

Pass `$terminologyClient` to `FHIRValueSetBindingValidator` in the constraint
validator factory (see Quick Start). When the client is `null` or a
`NullFHIRTerminologyClient`, no violation is raised for invalid codes against
extensible or preferred value sets — instead each skipped check emits a single
`fhir:unchecked-binding` INFO violation surfacing the coverage gap:

```php
$report = $service->validate($patient);

if ($report->hasUncheckedBindings()) {
    foreach ($report->uncheckedBindings() as $unchecked) {
        echo $unchecked->message . "\n";
        // "Terminology validation for value set http://… was skipped: no terminology client is configured."
    }
}
```

The message template can be overridden via the registry key
`FHIRValueSetBindingUnchecked`. To suppress the INFO violations, wire a real
terminology client or filter the `fhir:unchecked-binding` code in your
application layer.

---

### CachingFHIRTerminologyClient — terminology result caching

Wraps any `FHIRTerminologyClientInterface` to avoid repeated server calls for the same
code/value-set pair. An in-process array cache is always active for the lifetime of the
PHP request. A PSR-6 pool is optional for cross-request persistence.

**No pool — in-process cache only (zero dependencies):**

```php
use Ardenexal\FHIRTools\Component\Validation\CachingFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;

$terminologyClient = new CachingFHIRTerminologyClient(
    new HttpFHIRTerminologyClient($httpClient, 'https://tx.fhir.org/r4'),
);
```

**With a PSR-6 pool for cross-request persistence:**

```php
$terminologyClient = new CachingFHIRTerminologyClient(
    new HttpFHIRTerminologyClient($httpClient, 'https://tx.fhir.org/r4'),
    cache: $psrCache,
    ttl: 3600,   // 0 = no expiry (most pools treat expiresAfter(null) as forever)
);
```

**Composition with `PreferredServerAwareTerminologyClient`:**

```php
use Ardenexal\FHIRTools\Component\Validation\PreferredServerAwareTerminologyClient;

$terminologyClient = new PreferredServerAwareTerminologyClient(
    preferred: [
        new CachingFHIRTerminologyClient(
            new HttpFHIRTerminologyClient($httpClient, 'https://preferred.example.org/r4'),
            cache: $psrCache,
        ),
    ],
    fallback: new CachingFHIRTerminologyClient(
        new HttpFHIRTerminologyClient($httpClient, 'https://tx.fhir.org/r4'),
        cache: $psrCache,
    ),
);
```

> **Symfony bundle users:** set `fhir.validation.terminology_cache_pool` to a Symfony
> cache pool service ID (e.g. `cache.app`) to enable this decorator automatically — no
> manual wiring required. See the FHIRBundle README for details.

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

### FHIRTypeHierarchyResolverInterface — type-aware extension context resolution

Controls whether bare-type (e.g. `"HumanName"`, `"DomainResource"`) and foreign-root type-path
(e.g. `"ElementDefinition.binding"`) extension contexts are evaluated against the FHIR type of
the element they are applied to.

`FhirPropertyTypeHierarchyResolver` is the **default** (constructor default, and wired by
`FHIRBundle`). It is safe to leave on: resolution is monotonic — it only ever clears false
positives, never introduces a denial. Inject `NullFHIRTypeHierarchyResolver` to turn type
resolution off, in which case these contexts are deferred (never produce violations).

```php
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTypeHierarchyResolver;

// Default: resolves types from generated models (reflection, no codegen)
$typeResolver = new FhirPropertyTypeHierarchyResolver();

// Opt out: defer all bare-type / foreign-root context checks
$typeResolver = new NullFHIRTypeHierarchyResolver();
```

Pass `$typeResolver` as the `typeResolver` parameter of `FHIRValidationService` (omit to use
the default):

```php
$service = new FHIRValidationService($validator, $pathService, typeResolver: $typeResolver);
```

`FhirPropertyTypeHierarchyResolver` reads the `fhirType` field of `#[FhirProperty]` attributes
for declared element types, and walks the generated PHP class inheritance chain (reading
`#[FhirResource]`/`#[FHIRComplexType]`) for FHIR type hierarchies. It requires no codegen
changes — all generated R4, R4B, and R5 models already carry these attributes. Results are
cached per class to avoid repeated reflection.

> **Note:** FHIR type inheritance is evaluated — a context naming a supertype (e.g.
> `"DomainResource"`, `"Element"`) permits its subtypes. Resolution is monotonic: wiring the
> resolver only ever permits more, never introduces a denial, so it cannot create a false
> positive against an already-valid resource.

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
| `hasUncheckedBindings()` | `bool` | `true` when extensible/preferred binding checks were skipped (no terminology client) |
| `uncheckedBindings()` | `list<FHIRValidationViolation>` | The `fhir:unchecked-binding` violations, one per skipped binding check |
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
| `code` | `?string` | Raw violation code (e.g. `fhir:eval-error`, `fhir:unchecked-binding`), else the Symfony constraint code |

### FHIRViolationCode

Violation codes are set on Symfony constraint violations and used to derive severity:

| Constant | Value | Severity mapped to |
|---|---|---|
| `FHIRViolationCode::ERROR` | `'fhir:error'` | `error` |
| `FHIRViolationCode::WARNING` | `'fhir:warning'` | `warning` |
| `FHIRViolationCode::INFO` | `'fhir:info'` | `info` |
| `FHIRViolationCode::EVAL_ERROR` | `'fhir:eval-error'` | `info` |
| `FHIRViolationCode::UNCHECKED_BINDING` | `'fhir:unchecked-binding'` | `info` |

Built-in Symfony constraint codes (from `#[NotBlank]`, `#[Count]`, etc.) map to
`error` by default.

`FHIRViolationCode::EVAL_ERROR` denotes a FHIRPath invariant (or extension `contextInvariant`)
that the engine could not evaluate — for example, an invariant using a function the evaluator
does not yet support. Per the FHIR conformance spec, a tooling limitation must not be asserted as
instance non-conformance, so these surface at `info` severity rather than `error`. The raw code is
preserved on `FHIRValidationViolation::$code` so consumers can distinguish "could not evaluate" from
"genuinely failed".

`FHIRViolationCode::UNCHECKED_BINDING` denotes an extensible/preferred binding check that was
skipped because no real terminology client is configured (see Known Limitations). Like
`EVAL_ERROR`, it marks a coverage gap rather than non-conformance, surfaces at `info` severity,
and is queryable via `FHIRValidationReport::hasUncheckedBindings()` / `uncheckedBindings()`.

---

## Questionnaire Validation

Questionnaire/QuestionnaireResponse validation is handled by the standalone
`FHIRQuestionnaireValidator` service (GitHub #74, ADR-007). It validates a
`QuestionnaireResponse` against its source `Questionnaire` (R4, R4B, and R5) and
needs no constructor arguments. Use it alongside `FHIRValidationService` for
complete QuestionnaireResponse coverage:

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;

$questionnaireValidator = new FHIRQuestionnaireValidator();

// Validate the response structure (cardinality, bindings, invariants)
$structuralReport = $service->validate($response);

// Validate against the source Questionnaire (linkId, required, enableWhen, type)
$questionnaireReport = $questionnaireValidator->validate($questionnaire, $response);

// Combine reports for a unified view
$merged = new FHIRValidationReport([
    ...$structuralReport->violations,
    ...$questionnaireReport->violations,
]);
```

Implemented rules and severities:

| Rule | Severity |
|---|---|
| Response item `linkId` must exist in the source Questionnaire | `error` |
| Response items must sit at the position their `linkId` is declared at in the Questionnaire hierarchy | `error` |
| Required, enabled items must be answered when status is `completed`/`amended`, checked **per parent instance**: each instance of a repeating parent is checked independently, and an absent optional parent exempts its children | `error` |
| A required group needs at least one answered descendant question (presence alone is not enough) | `error` |
| Non-repeating items: at most one occurrence per parent and one answer | `error` |
| Answer value type must match the declared item type | `warning` |
| Items present while their `enableWhen` conditions are unsatisfied | `warning` |
| `enableWhen.question` must reference a known `linkId` | `warning` |

Pass `strictStatus: false` to skip the required-item check regardless of response
status (useful for drafts). The validator never resolves
`QuestionnaireResponse.questionnaire` canonical URLs — callers supply the source
`Questionnaire` object. SDC extensions (`enableWhenExpression`, `answerExpression`,
calculated expressions, regex constraints) and R5 `answerConstraint` are not yet
covered. enableWhen answers are looked up response-globally — a documented
approximation of the spec's nearest-occurrence resolution that is exact whenever the
referenced question occurs once; see the ADR-007 addendum for the conformance plan.
Violations carry `FHIRQuestionnaireConstraint::class` in `constraintClass` so they
can be distinguished after merging.

### Conformance coverage

The validator is exercised against the official `fhir/fhir-test-cases` QuestionnaireResponse
corpus via `FHIRQuestionnaireConformanceTest` (its own `questionnaire-spec` suite — run with
`composer test-ai-questionnaire-spec`). Of the 78 eligible R4 cross-resource cases (a
QuestionnaireResponse plus its source Questionnaire):

- **41 are asserted** — error/warning counts match seeded expectations; for these the validator's
  verdict agrees with the HL7 Java validator's error-presence (answer-type mismatches are reported
  at `warning` rather than `error`, by design).
- **36 are out of scope** and left incomplete (not silently passing) — they test rules this
  validator does not implement (answerOption/value-set membership, min/max, regex, Quantity units,
  Attachment constraints, Reference target types, SDC `enableWhenExpression`). See the plan backlog.
- **1 is skipped** — its supporting resource is not a Questionnaire.

The validator reports **no false-positive errors** across the corpus (its error count never exceeds
the reference validator's).

---

## OperationOutcome Mapping

`FHIRValidationService::validateForOperation()` returns a standards-compliant
`OperationOutcomeResource` for FHIR `$validate` operation responses. Pass the target
FHIR version (`'R4'`, `'R4B'`, or `'R5'`) to receive a version-typed resource:

```php
$outcome = $service->validateForOperation($patient, fhirVersion: 'R4');
// $outcome is an R4\Resource\OperationOutcomeResource

$outcome = $service->validateForOperation($patient, mode: 'create', fhirVersion: 'R5');
// $outcome is an R5\Resource\OperationOutcomeResource
```

Violations map as follows:

| `FHIRValidationViolation::$severity` | `OperationOutcomeIssue::$severity` | `OperationOutcomeIssue::$code` |
|---|---|---|
| `error` | `error` | `invariant` (FHIRPath), `value` (binding), `invalid` (default) |
| `warning` | `warning` | same mapping |
| `info` (`fhir:eval-error`) | `information` | `not-supported` |
| `info` (`fhir:unchecked-binding`) | `information` | `not-supported` |
| `info` (`fhir:info`, general) | `information` | `informational` |

When no violations are found, the outcome contains a single `information`-severity issue:
`"No issues found — resource is valid."`

The `$validate` operation endpoint format per spec:
```
POST [base]/[ResourceType]/$validate?profile=[profile-url]
```

The operation always returns HTTP 200 OK when validation ran successfully, regardless
of findings. Structural errors (unparseable JSON/XML) may produce 4xx responses before
this library is reached.

`mode=delete` is not supported by this library — referential integrity checks require
a FHIR server context. A call with `mode='delete'` returns an information-severity
outcome explaining this limitation.

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
- [ADR-007: Questionnaire validator architecture](./../../../.goat-flow/decisions/ADR-007-questionnaire-validator-architecture.md)
- [GitHub Epic #76](https://github.com/Ardenexal/php-fhir-tools/issues/76)
