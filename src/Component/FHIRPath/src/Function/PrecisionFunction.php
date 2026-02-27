<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;

/**
 * FHIRPath precision() function.
 *
 * Returns the precision of the input value using the FHIRPath positional
 * numbering scheme:
 *
 *  - FHIRPathDecimal:  number of digits after the decimal point (trailing
 *                      zeros preserved, e.g. 1.58700 → 5)
 *  - float/integer:    number of digits after decimal point (0 for integers)
 *  - Date string:
 *                        YYYY         → 4
 *                        YYYY-MM      → 6
 *                        YYYY-MM-DD   → 8
 *  - DateTime string:
 *                        YYYY-MM-DDTHH           → 10
 *                        YYYY-MM-DDTHH:MM        → 12
 *                        YYYY-MM-DDTHH:MM:SS     → 14
 *                        YYYY-MM-DDTHH:MM:SS.mmm → 17
 *  - Time string:
 *                        THH          → 2
 *                        THH:MM       → 4
 *                        THH:MM:SS    → 6
 *                        THH:MM:SS.mmm → 9
 *  - Anything else:    empty
 */
final class PrecisionFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('precision');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $value = $input->first();

        // FHIRPathDecimal preserves trailing zeros in its string, so ->precision is exact
        if ($value instanceof FHIRPathDecimal) {
            return Collection::single($value->precision);
        }

        if ($value instanceof FHIRPathTemporalTypeInterface) {
            $p = $this->temporalPrecision($value->getValue(), $value->getTemporalTypeName() === 'time');

            return $p !== null ? Collection::single($p) : Collection::empty();
        }

        if (is_float($value)) {
            return Collection::single($this->floatPrecision($value));
        }

        if (is_int($value)) {
            return Collection::single(0);
        }

        if (is_string($value)) {
            $p = $this->stringPrecision($value);

            return $p !== null ? Collection::single($p) : Collection::empty();
        }

        return Collection::empty();
    }

    /**
     * Count digits after the decimal point in a float.
     * NOTE: PHP floats may strip trailing zeros; use FHIRPathDecimal for exact results.
     */
    private function floatPrecision(float $value): int
    {
        $str = (string) $value;

        $dotPos = strpos($str, '.');
        if ($dotPos === false) {
            return 0;
        }

        $fractionalPart = substr($str, $dotPos + 1);
        $ePos           = stripos($fractionalPart, 'e');
        if ($ePos !== false) {
            $fractionalPart = substr($fractionalPart, 0, $ePos);
        }

        return strlen($fractionalPart);
    }

    /**
     * Compute the positional precision of a temporal value string.
     *
     * @param string $value     Bare ISO string (@ prefix already stripped by the value object)
     * @param bool   $timeOnly  True for FHIRPathTime (T-prefixed time-only strings)
     */
    private function temporalPrecision(string $value, bool $timeOnly): ?int
    {
        if ($timeOnly) {
            return $this->timePrecision($value);
        }

        // Detect datetime vs date by presence of T
        if (str_contains($value, 'T')) {
            return $this->dateTimePrecision($value);
        }

        return $this->datePrecision($value);
    }

    /**
     * Positional precision for date strings (YYYY, YYYY-MM, YYYY-MM-DD).
     */
    private function datePrecision(string $value): ?int
    {
        return match (true) {
            (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) => 8,
            (bool) preg_match('/^\d{4}-\d{2}$/', $value)        => 6,
            (bool) preg_match('/^\d{4}$/', $value)              => 4,
            default                                              => null,
        };
    }

    /**
     * Positional precision for datetime strings.
     * The time component after T may have a trailing timezone (+HH:MM/-HH:MM/Z).
     */
    private function dateTimePrecision(string $value): ?int
    {
        // Strip timezone suffix
        $stripped = (string) preg_replace('/([+-]\d{2}:\d{2}|Z)$/', '', $value);

        return match (true) {
            (bool) preg_match('/T\d{2}:\d{2}:\d{2}\.\d+$/', $stripped) => 17,
            (bool) preg_match('/T\d{2}:\d{2}:\d{2}$/', $stripped)       => 14,
            (bool) preg_match('/T\d{2}:\d{2}$/', $stripped)             => 12,
            (bool) preg_match('/T\d{2}$/', $stripped)                   => 10,
            default                                                      => null,
        };
    }

    /**
     * Positional precision for time-only strings (T-prefixed).
     */
    private function timePrecision(string $value): ?int
    {
        // Normalise: strip leading T if present
        $t = str_starts_with($value, 'T') ? substr($value, 1) : $value;

        return match (true) {
            (bool) preg_match('/^\d{2}:\d{2}:\d{2}\.\d+$/', $t) => 9,
            (bool) preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)       => 6,
            (bool) preg_match('/^\d{2}:\d{2}$/', $t)             => 4,
            (bool) preg_match('/^\d{2}$/', $t)                   => 2,
            default                                               => null,
        };
    }

    /**
     * Detect precision from a plain string (resource property values arrive as strings).
     * Tries time-only format first, then datetime, then date.
     */
    private function stringPrecision(string $value): ?int
    {
        // Strip optional @ prefix
        $bare = ltrim($value, '@');

        if (str_starts_with($bare, 'T')) {
            return $this->timePrecision($bare);
        }

        if (str_contains($bare, 'T')) {
            return $this->dateTimePrecision($bare);
        }

        if (preg_match('/^\d{4}/', $bare)) {
            return $this->datePrecision($bare);
        }

        return null;
    }
}
