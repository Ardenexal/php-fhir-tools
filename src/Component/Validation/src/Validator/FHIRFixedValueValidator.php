<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a property exactly equals the fixed value declared in a StructureDefinition.
 *
 * Enforces a #[FHIRFixedValue] constraint: the instance value must match the fixed scalar
 * exactly. FHIR primitive wrapper objects (which are \Stringable) are coerced to string before
 * comparison so correctly-valued wrappers are not flagged. A mismatch raises an ERROR violation.
 */
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

        // FHIR primitive wrapper objects (e.g. UriPrimitive, StringPrimitive) implement \Stringable.
        // Fixed values in StructureDefinitions are always PHP scalars, so coerce wrappers to string
        // before comparison to avoid false violations on correctly-valued wrapper properties.
        $comparable = $value instanceof \Stringable ? (string) $value : $value;

        if ($comparable !== $constraint->value) {
            $override = $this->messageRegistry->getOverride('FHIRFixedValue');
            $this->context->buildViolation($override ?? self::DEFAULT_MESSAGE)
                ->setParameters(['{{ value }}' => (string) $comparable, '{{ fixed }}' => (string) $constraint->value])
                ->setInvalidValue($value)
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }
}
