<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FHIRValueSetBindingValidator extends ConstraintValidator
{
    public const string DEFAULT_MISSING_ENUM_MESSAGE = 'Value set {{ url }} has no generated enum class; required-strength binding cannot be validated.';

    public const string DEFAULT_INVALID_VALUE_MESSAGE = 'The value {{ value }} is not a valid case of value set {{ url }}.';

    /**
     * @param string[] $enumNamespaceRoots Namespace roots to probe for generated enum classes,
     *                                     e.g. ['Ardenexal\FHIRTools\Component\Models\R4\Enum']
     */
    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly array $enumNamespaceRoots = [],
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

        $className = $this->classNameFromUrl($constraint->valueSetUrl);
        $enumFqcn  = $this->resolveEnumFqcn($className);

        if ($enumFqcn === null) {
            if ($constraint->strength === 'required') {
                $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
                $this->context->buildViolation($override ?? self::DEFAULT_MISSING_ENUM_MESSAGE)
                    ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                    ->setCode(FHIRViolationCode::ERROR)
                    ->addViolation();
            }

            return;
        }

        /** @var class-string<\BackedEnum> $enumFqcn */
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
     * @param class-string<\BackedEnum> $enumFqcn
     */
    private function isValidEnumCase(string $enumFqcn, mixed $value): bool
    {
        if ($value instanceof $enumFqcn) {
            return true;
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

        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}
