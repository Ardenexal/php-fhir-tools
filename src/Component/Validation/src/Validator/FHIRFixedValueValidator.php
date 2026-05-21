<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FHIRFixedValueValidator extends ConstraintValidator
{
    public const string DEFAULT_MESSAGE = 'The value {{ value }} does not match the required fixed value {{ fixed }}.';

    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRFixedValue) {
            throw new UnexpectedTypeException($constraint, FHIRFixedValue::class);
        }

        if ($value === null) {
            return;
        }

        if ($value !== $constraint->value) {
            $override = $this->messageRegistry->getOverride('FHIRFixedValue');
            $this->context->buildViolation($override ?? self::DEFAULT_MESSAGE)
                ->setParameters(['{{ value }}' => (string) $value, '{{ fixed }}' => (string) $constraint->value])
                ->setInvalidValue($value)
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }
}
