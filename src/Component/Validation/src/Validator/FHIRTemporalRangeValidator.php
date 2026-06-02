<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTemporalRange;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FHIRTemporalRangeValidator extends ConstraintValidator
{
    private const string MSG_BELOW_MIN = 'The value {{ value }} is before the minimum {{ min }}.';

    private const string MSG_ABOVE_MAX = 'The value {{ value }} is after the maximum {{ max }}.';

    private const string MSG_INVALID_VALUE = 'The value {{ value }} is not a valid FHIR {{ type }} string.';

    private const string MSG_INVALID_BOUND = 'The configured {{ side }} bound {{ bound }} is not a valid FHIR {{ type }} string.';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRTemporalRange) {
            throw new UnexpectedTypeException($constraint, FHIRTemporalRange::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            return;
        }

        if ($constraint->temporalType === 'time') {
            $this->validateTime($value, $constraint);

            return;
        }

        $this->validateDateLike($value, $constraint);
    }

    private function validateTime(string $value, FHIRTemporalRange $constraint): void
    {
        if ($constraint->minValue !== null && strcmp($value, $constraint->minValue) < 0) {
            $this->context->buildViolation(self::MSG_BELOW_MIN)
                ->setParameters(['{{ value }}' => $value, '{{ min }}' => $constraint->minValue])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }

        if ($constraint->maxValue !== null && strcmp($value, $constraint->maxValue) > 0) {
            $this->context->buildViolation(self::MSG_ABOVE_MAX)
                ->setParameters(['{{ value }}' => $value, '{{ max }}' => $constraint->maxValue])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }

    private function validateDateLike(string $value, FHIRTemporalRange $constraint): void
    {
        $minDt = null;
        $maxDt = null;

        if ($constraint->minValue !== null) {
            $minDt = $this->parseBound($constraint->minValue, $constraint->temporalType);

            if ($minDt === null) {
                $this->context->buildViolation(self::MSG_INVALID_BOUND)
                    ->setParameters([
                        '{{ side }}'  => 'minimum',
                        '{{ bound }}' => $constraint->minValue,
                        '{{ type }}'  => $constraint->temporalType,
                    ])
                    ->setCode(FHIRViolationCode::WARNING)
                    ->addViolation();
            }
        }

        if ($constraint->maxValue !== null) {
            $maxDt = $this->parseBound($constraint->maxValue, $constraint->temporalType);

            if ($maxDt === null) {
                $this->context->buildViolation(self::MSG_INVALID_BOUND)
                    ->setParameters([
                        '{{ side }}'  => 'maximum',
                        '{{ bound }}' => $constraint->maxValue,
                        '{{ type }}'  => $constraint->temporalType,
                    ])
                    ->setCode(FHIRViolationCode::WARNING)
                    ->addViolation();
            }
        }

        // min-side: expand partial date to start of period
        $valueDtForMin = $this->expandForMinComparison($value, $constraint->temporalType);

        if ($valueDtForMin === null) {
            $this->context->buildViolation(self::MSG_INVALID_VALUE)
                ->setParameters(['{{ value }}' => $value, '{{ type }}' => $constraint->temporalType])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();

            return;
        }

        if ($minDt !== null && $valueDtForMin < $minDt) {
            $this->context->buildViolation(self::MSG_BELOW_MIN)
                ->setParameters(['{{ value }}' => $value, '{{ min }}' => $constraint->minValue])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }

        // max-side: expand partial date to end of period
        $valueDtForMax = $this->expandForMaxComparison($value, $constraint->temporalType);

        if ($maxDt !== null && $valueDtForMax !== null && $valueDtForMax > $maxDt) {
            $this->context->buildViolation(self::MSG_ABOVE_MAX)
                ->setParameters(['{{ value }}' => $value, '{{ max }}' => $constraint->maxValue])
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }

    private function parseBound(string $bound, string $temporalType): ?\DateTimeImmutable
    {
        return match ($temporalType) {
            'instant' => \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $bound) ?: null,
            default   => $this->parsePartialOrFullDate($bound, forMax: false),
        };
    }

    private function expandForMinComparison(string $value, string $temporalType): ?\DateTimeImmutable
    {
        return match ($temporalType) {
            'instant' => \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $value) ?: null,
            default   => $this->parsePartialOrFullDate($value, forMax: false),
        };
    }

    private function expandForMaxComparison(string $value, string $temporalType): ?\DateTimeImmutable
    {
        return match ($temporalType) {
            'instant' => \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $value) ?: null,
            default   => $this->parsePartialOrFullDate($value, forMax: true),
        };
    }

    /**
     * Parses a FHIR date/dateTime string, expanding partial dates to period boundaries.
     *
     * FHIR date formats: YYYY | YYYY-MM | YYYY-MM-DD | YYYY-MM-DDThh:mm:ss[Z|±hh:mm]
     * Conservative expansion (see ADR-006):
     *   YYYY        → forMax=false: YYYY-01-01, forMax=true: YYYY-12-31
     *   YYYY-MM     → forMax=false: YYYY-MM-01, forMax=true: last day of month
     *   YYYY-MM-DD  → exact
     */
    private function parsePartialOrFullDate(string $value, bool $forMax): ?\DateTimeImmutable
    {
        $len = strlen($value);

        if ($len === 4 && ctype_digit($value)) {
            $expanded = $forMax ? $value . '-12-31' : $value . '-01-01';

            return new \DateTimeImmutable($expanded);
        }

        if ($len === 7 && preg_match('/^\d{4}-\d{2}$/', $value)) {
            if ($forMax) {
                $firstOfMonth = new \DateTimeImmutable($value . '-01');
                $lastDay      = $firstOfMonth->format('t');

                return new \DateTimeImmutable($value . '-' . $lastDay);
            }

            return new \DateTimeImmutable($value . '-01');
        }

        // Full date or dateTime with optional timezone — let PHP parse it
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d', substr($value, 0, 10));

        if ($dt === false) {
            return null;
        }

        if ($len > 10) {
            // Has time component — parse full string for accurate instant comparison
            $full = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $value)
                 ?: \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $value)
                 ?: \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', $value)
                 ?: $dt;

            return $full;
        }

        return $dt;
    }
}
