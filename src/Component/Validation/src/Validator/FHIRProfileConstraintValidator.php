<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
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

        // Validate the property value against the inner constraint in a fresh context so we can
        // re-emit each violation via $this->context->buildViolation(). This keeps the outer
        // FHIRProfileConstraint as the violation's constraint, enabling profile-group attribution
        // in FHIRValidationService (getConstraint() returns FHIRProfileConstraint, not Count etc.).
        $innerViolations = $this->context->getValidator()->validate($propertyValue, $innerConstraint, ['Default']);

        foreach ($innerViolations as $v) {
            $innerPath = $v->getPropertyPath();
            $path      = match (true) {
                $innerPath    === ''         => $constraint->path,
                $innerPath[0] === '['        => $constraint->path . $innerPath,
                default                      => $constraint->path . '.' . $innerPath,
            };

            $this->context->buildViolation($v->getMessageTemplate(), $v->getParameters())
                ->atPath($path)
                ->setCode($v->getCode() ?? FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }
}
