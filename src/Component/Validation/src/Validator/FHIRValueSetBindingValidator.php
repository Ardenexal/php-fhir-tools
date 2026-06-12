<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
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
        $className = $this->classNameFromUrl($constraint->valueSetUrl);
        $enumFqcn  = $this->resolveEnumFqcn($className);

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

    /** @return class-string<\BackedEnum>|null */
    private function resolveEnumFqcn(string $className): ?string
    {
        foreach ($this->enumNamespaceRoots as $root) {
            $fqcn = $root . '\\' . $className;
            if (class_exists($fqcn) && enum_exists($fqcn)) {
                /** @var class-string<\BackedEnum> $fqcn */
                return $fqcn;
            }
        }

        return null;
    }

    private function classNameFromUrl(string $valueSetUrl): string
    {
        $urlPath = parse_url($valueSetUrl, PHP_URL_PATH) ?? $valueSetUrl;
        $name    = basename((string) $urlPath);
        $name    = (string) strstr($name, '|', true) ?: $name;

        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}
