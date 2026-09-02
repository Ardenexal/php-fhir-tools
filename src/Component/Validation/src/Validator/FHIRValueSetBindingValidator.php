<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a coded value conforms to the value set bound to a property.
 *
 * Enforces a #[FHIRValueSetBinding] constraint, branching on binding strength. `required`
 * bindings are checked against the generated backed-enum for the value set (falling back to the
 * terminology client when no enum exists); `extensible`/`preferred` bindings are checked via the
 * terminology client. `example` bindings are ignored. When no real terminology client is
 * configured, non-required checks are skipped and surfaced as a single fhir:unchecked-binding
 * INFO violation (issue #71) that does not affect overall validity.
 */
final class FHIRValueSetBindingValidator extends ConstraintValidator
{
    public const string DEFAULT_MISSING_ENUM_MESSAGE = 'Required binding for value set {{ url }} could not be validated: no enum class generated.';

    public const string DEFAULT_INVALID_VALUE_MESSAGE = 'The value {{ value }} is not a valid case of value set {{ url }}.';

    public const string DEFAULT_UNCHECKED_BINDING_MESSAGE = 'Terminology validation for value set {{ url }} was skipped: no terminology client is configured.';

    /**
     * @param string[]                            $enumNamespaceRoots Namespace roots to probe for generated enum classes,
     *                                                                e.g. ['Ardenexal\FHIRTools\Component\Models\R4\Enum']
     * @param FHIRTerminologyClientInterface|null $terminologyClient  Null or NullFHIRTerminologyClient → extensible/preferred checks
     *                                                                are skipped and surfaced as fhir:unchecked-binding INFO violations
     */
    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly array $enumNamespaceRoots = [],
        private readonly ?FHIRTerminologyClientInterface $terminologyClient = null,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRValueSetBinding) {
            throw new UnexpectedTypeException($constraint, FHIRValueSetBinding::class);
        }

        if ($value === null) {
            return;
        }

        // An empty repeating element carries no codes, so there is no membership question to answer.
        // Only `null` was treated as absent before, which was enough while the deserializer left
        // repeating properties uninitialized (Symfony reads those as null). Once they are initialised
        // to `[]`, every unpopulated coded array — `ElementDefinition.type.aggregation` is the loudest —
        // reached validateRequired() and produced a "no enum class generated" warning about nothing.
        if ($value === []) {
            return;
        }

        // FHIR primitives that carry only extensions (e.g. `_status`) have no
        // underlying value. Cast-to-string yields "" — treat as absent so that
        // NotBlank handles the required-field check rather than this validator.
        if ($value instanceof \Stringable && (string) $value === '') {
            return;
        }

        if ($constraint->strength === 'required') {
            $this->validateRequired($value, $constraint);

            return;
        }

        $this->validateNonRequired($value, $constraint);
    }

    private function validateRequired(mixed $value, FHIRValueSetBinding $constraint): void
    {
        $enumFqcn = $this->resolveBoundEnum($constraint);

        if ($enumFqcn === null) {
            if ($this->terminologyClient !== null) {
                $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
                foreach (is_array($value) ? $value : [$value] as $item) {
                    if (!$this->terminologyClient->validateCode($constraint->valueSetUrl, $item)) {
                        $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                            ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->valueSetUrl])
                            ->setInvalidValue($item)
                            ->setCode(FHIRViolationCode::ERROR)
                            ->addViolation();
                    }
                }

                return;
            }

            $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
            $this->context->buildViolation($override ?? self::DEFAULT_MISSING_ENUM_MESSAGE)
                ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        /** @var class-string<\BackedEnum> $enumFqcn */
        if (is_array($value)) {
            $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
            foreach ($value as $item) {
                if (!self::isTestableCode($item)) {
                    continue;
                }

                if (!$this->isValidEnumCase($enumFqcn, $item)) {
                    $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                        ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->valueSetUrl])
                        ->setInvalidValue($item)
                        ->setCode(FHIRViolationCode::ERROR)
                        ->addViolation();
                }
            }

            return;
        }

        if (!self::isTestableCode($value)) {
            return;
        }

        if (!$this->isValidEnumCase($enumFqcn, $value)) {
            $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
            $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                ->setParameters(['{{ value }}' => (string) $value, '{{ url }}' => $constraint->valueSetUrl])
                ->setInvalidValue($value)
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }

    /**
     * Validates extensible/preferred bindings via the terminology client.
     * When no real client is configured (null or NullFHIRTerminologyClient), the check is
     * skipped and a single fhir:unchecked-binding INFO violation surfaces the coverage gap
     * (issue #71); it does not affect FHIRValidationReport::isValid().
     * Violations use WARNING by default; strict=true escalates to ERROR.
     * When maxValueSetUrl is set, values outside it always produce ERROR regardless of strict.
     */
    private function validateNonRequired(mixed $value, FHIRValueSetBinding $constraint): void
    {
        // Example-strength bindings are documentation only (ADR-004): never validated,
        // never surfaced as unchecked. The generator does not emit the attribute for them
        // (FHIRModelGenerator::shouldEmitBindingAttribute), so this guards hand-written
        // constraints and future generator changes.
        if ($constraint->strength === 'example') {
            return;
        }

        if ($this->terminologyClient === null || $this->terminologyClient instanceof NullFHIRTerminologyClient) {
            $override = $this->messageRegistry->getOverride('FHIRValueSetBindingUnchecked');
            $this->context->buildViolation($override ?? self::DEFAULT_UNCHECKED_BINDING_MESSAGE)
                ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
                ->addViolation();

            return;
        }

        $override    = $this->messageRegistry->getOverride('FHIRValueSetBinding');
        $bindingCode = $constraint->strict ? FHIRViolationCode::ERROR : FHIRViolationCode::WARNING;

        if (!$this->terminologyClient->validateCode($constraint->valueSetUrl, $value)) {
            $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                ->setParameters(['{{ value }}' => (string) $value, '{{ url }}' => $constraint->valueSetUrl])
                ->setInvalidValue($value)
                ->setCode($bindingCode)
                ->addViolation();
        }

        if ($constraint->maxValueSetUrl !== null && !$this->terminologyClient->validateCode($constraint->maxValueSetUrl, $value)) {
            $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                ->setParameters(['{{ value }}' => (string) $value, '{{ url }}' => $constraint->maxValueSetUrl])
                ->setInvalidValue($value)
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }

    /**
     * @param class-string<\BackedEnum> $enumFqcn
     */
    private function isValidEnumCase(string $enumFqcn, mixed $value): bool
    {
        if ($value instanceof $enumFqcn) {
            return true;
        }

        // Array properties (isArray: true) pass the whole PHP array here;
        // validate each element individually.
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!$this->isValidEnumCase($enumFqcn, $item)) {
                    return false;
                }
            }

            return true;
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        if (is_string($value) || is_int($value)) {
            return $enumFqcn::tryFrom($value) !== null;
        }

        return false;
    }

    /**
     * Resolve a generated enum for this value set, or null when membership cannot be decided here.
     *
     * The backed check is load-bearing, not defensive. `enum_exists()` is also true for a *pure*
     * enum, and some value sets cannot be enumerated at all — `AllLanguages` covers the whole of
     * BCP-47, so the generator emits `enum AllLanguages {}` with no cases and no backing type.
     * Calling `tryFrom()` on that is a fatal `Error: Call to undefined method`, which took out four
     * R5 cases mid-validation. Returning null instead routes to the existing "no generated enum
     * class" warning — the honest answer for a binding we cannot check offline.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function resolveEnumFqcn(string $className): ?string
    {
        foreach ($this->enumNamespaceRoots as $root) {
            $fqcn = $root . '\\' . $className;

            // Folds the existence, enum and backing checks into one question: a candidate name built
            // from a namespace root plus a class name is expected to miss most of the time.
            if (!$this->attributes->isBackedEnum($fqcn)) {
                continue;
            }

            return $fqcn;
        }

        return null;
    }

    /**
     * The generated backed-enum bound to this constraint, or null when membership cannot be decided.
     *
     * Prefers `$constraint->enumClass`, which the generator resolves from the ValueSet's `name` via
     * `ClassNameResolver`. The URL cannot yield it: `.../ValueSet/item-type` names the class
     * `QuestionnaireItemType` and `http-verb` names `HTTPVerb`. The `classNameFromUrl()` fallback is
     * kept for hand-written constraints and models generated before the attribute carried the class.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function resolveBoundEnum(FHIRValueSetBinding $constraint): ?string
    {
        if ($constraint->enumClass !== null) {
            $direct = $this->qualifyEnum($constraint->enumClass);
            if ($direct !== null && $this->enumDeclaresValueSet($direct, $constraint->valueSetUrl)) {
                return $direct;
            }
        }

        return $this->resolveEnumFqcn($this->classNameFromUrl($constraint->valueSetUrl));
    }

    /**
     * Accept either a bare class name or an already-qualified FQCN, applying the same backed-enum
     * check as `resolveEnumFqcn()`. That check is load-bearing rather than defensive: `AllLanguages`
     * covers the whole of BCP-47, so the generator emits a caseless, unbacked enum for it, and
     * calling `tryFrom()` on that is a fatal `Error`.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function qualifyEnum(string $enumClass): ?string
    {
        if (str_contains($enumClass, '\\')) {
            return $this->isUsableBackedEnum($enumClass) ? $enumClass : null;
        }

        return $this->resolveEnumFqcn($enumClass);
    }

    /**
     * Whether an enum was generated from exactly the value set this binding names.
     *
     * The class name alone cannot answer this. `ClassNameResolver` maps
     * `.../ValueSet/medication-statement-status` and `.../ValueSet/medication-status` to the same name
     * `MedicationStatusCodes`, whose enum holds only the latter's three codes — binding on the name
     * alone rejected the legal `MedicationStatement.status` code `unknown`. The generator therefore
     * stamps each enum with its own source URL, and a mismatch here falls through to the "no enum
     * class generated" warning rather than producing a false positive.
     *
     * An enum with no `#[FHIRValueSetSource]` is rejected too: models generated before the attribute
     * existed cannot be verified, and an unverifiable enum is exactly what this guards against. Those
     * fall back to the URL-derived lookup, which is the pre-existing behaviour.
     *
     * @param class-string $enumFqcn
     */
    private function enumDeclaresValueSet(string $enumFqcn, string $boundValueSetUrl): bool
    {
        $attributes = $this->attributes->classAttributes($enumFqcn, FHIRValueSetSource::class);
        if ($attributes === []) {
            return false;
        }

        $declared = $attributes[0]->url;

        return self::bareUrl($declared) === self::bareUrl($boundValueSetUrl);
    }

    /** Strip the `|4.0.1` version suffix a binding URL carries but a value set's own URL does not. */
    private static function bareUrl(string $url): string
    {
        return (string) strstr($url, '|', true) ?: $url;
    }

    /** @phpstan-assert-if-true class-string<\BackedEnum> $fqcn */
    private function isUsableBackedEnum(string $fqcn): bool
    {
        return $this->attributes->isBackedEnum($fqcn);
    }

    /**
     * Whether a value can be tested for enum membership at all.
     *
     * A binding may be declared on a complex element (`CodeableConcept`, `Coding`), whose value is an
     * object that is not `Stringable`. Casting one for the violation message is a fatal `Error` — it
     * turned 12 R4 cases into `validate-crashed` the first time enum resolution was repaired. Deciding
     * membership from `coding[].code` is a separate capability; until it exists, decline rather than
     * crash or guess.
     */
    private static function isTestableCode(mixed $value): bool
    {
        return is_string($value) || is_int($value) || $value instanceof \Stringable;
    }

    private function classNameFromUrl(string $valueSetUrl): string
    {
        $urlPath = parse_url($valueSetUrl, PHP_URL_PATH) ?? $valueSetUrl;
        $name    = basename((string) $urlPath);
        $name    = (string) strstr($name, '|', true) ?: $name;

        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}
