<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Validates a FHIR profile constraint at class level.
 *
 * Reads the property value at FHIRProfileConstraint::$path via PropertyAccessor, instantiates
 * the declared inner Symfony constraint with the provided options, and delegates validation to
 * the current context's validator. Group propagation is handled by Symfony — this validator is
 * only invoked when the constraint's assigned groups are active.
 *
 * @author Ardenexal
 */
final class FHIRProfileConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRProfileConstraint) {
            throw new UnexpectedTypeException($constraint, FHIRProfileConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $propertyValue = $this->propertyAccessor->getValue($value, $constraint->path);

        $innerConstraint = new ($constraint->constraint)(...$constraint->options);

        // Inner constraints belong to the Default group; the current context group is the profile
        // URL (the group that activated this class-level constraint). Pass Default explicitly so
        // Symfony does not skip the inner constraint for group-mismatch.
        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->atPath($constraint->path)
            ->validate($propertyValue, $innerConstraint, ['Default']);
    }
}
