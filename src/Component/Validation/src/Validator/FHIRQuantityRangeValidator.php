<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRQuantityRange;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates a FHIR Quantity value against the min/max bounds declared in a StructureDefinition.
 *
 * Enforces a #[FHIRQuantityRange] constraint. A value below min or above max raises an ERROR.
 * Because units cannot be safely converted here, comparison only proceeds when the instance and
 * bound share the same system+code; an approximate value (one carrying a comparator), a missing
 * unit, a malformed bound, or a unit mismatch is surfaced as a WARNING rather than a hard failure.
 */
final class FHIRQuantityRangeValidator extends ConstraintValidator
{
    private const string MSG_BELOW_MIN = 'The value {{ value }} is below the minimum {{ min }} {{ unit }}.';

    private const string MSG_ABOVE_MAX = 'The value {{ value }} exceeds the maximum {{ max }} {{ unit }}.';

    private const string MSG_CROSS_UNIT = 'Cannot verify quantity bound: instance unit {{ instanceCode }} differs from bound unit {{ boundCode }}.';

    private const string MSG_MISSING_UNIT = 'Cannot verify quantity bound: instance is missing system or code.';

    private const string MSG_MALFORMED_BOUND = 'Cannot verify quantity {{ side }} bound: bound is missing system or code.';

    private const string MSG_COMPARATOR = 'Cannot verify quantity bound: instance value has a comparator and is not a precise measurement.';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRQuantityRange) {
            throw new UnexpectedTypeException($constraint, FHIRQuantityRange::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            return;
        }

        // Instance comparator means the value is approximate — comparison is unreliable
        $comparator = $value->comparator ?? null;
        if ($comparator !== null) {
            $this->context->buildViolation(self::MSG_COMPARATOR)
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        $numericStr = $value->value ?? null;
        if ($numericStr === null) {
            return;
        }

        $instanceSystem = isset($value->system) ? (string) $value->system : null;
        $instanceCode   = isset($value->code) ? (string) $value->code : null;
        $instanceFloat  = (float) $numericStr;

        if ($constraint->minValue !== null) {
            $this->checkBound($instanceFloat, $instanceSystem, $instanceCode, $constraint->minValue, 'min');
        }

        if ($constraint->maxValue !== null) {
            $this->checkBound($instanceFloat, $instanceSystem, $instanceCode, $constraint->maxValue, 'max');
        }
    }

    /** @param array{value: float, system: ?string, code: ?string} $bound */
    private function checkBound(float $instanceValue, ?string $instanceSystem, ?string $instanceCode, array $bound, string $side): void
    {
        $boundSystem = $bound['system'] ?? null;
        $boundCode   = $bound['code']   ?? null;

        if ($boundSystem === null || $boundCode === null) {
            $this->context->buildViolation(self::MSG_MALFORMED_BOUND)
                ->setParameter('{{ side }}', $side === 'min' ? 'minimum' : 'maximum')
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        if ($instanceSystem === null || $instanceCode === null) {
            $this->context->buildViolation(self::MSG_MISSING_UNIT)
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        if ($instanceSystem !== $boundSystem || $instanceCode !== $boundCode) {
            $this->context->buildViolation(self::MSG_CROSS_UNIT)
                ->setParameters([
                    '{{ instanceCode }}' => $instanceCode,
                    '{{ boundCode }}'    => $boundCode,
                ])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        $boundValue = (float) $bound['value'];

        if ($side === 'min' && $instanceValue < $boundValue) {
            $this->context->buildViolation(self::MSG_BELOW_MIN)
                ->setParameters([
                    '{{ value }}' => (string) $instanceValue,
                    '{{ min }}'   => (string) $boundValue,
                    '{{ unit }}'  => $instanceCode,
                ])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }

        if ($side === 'max' && $instanceValue > $boundValue) {
            $this->context->buildViolation(self::MSG_ABOVE_MAX)
                ->setParameters([
                    '{{ value }}' => (string) $instanceValue,
                    '{{ max }}'   => (string) $boundValue,
                    '{{ unit }}'  => $instanceCode,
                ])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }
}
