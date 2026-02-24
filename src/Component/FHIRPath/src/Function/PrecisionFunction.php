<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath precision() function.
 *
 * Returns the precision of the input value:
 *  - Decimal:       number of digits after the decimal point (e.g. 1.58 → 2)
 *  - Integer:       0 (no fractional digits)
 *  - Date string:   number of date/time components present:
 *                     "2020"                     → 1  (year)
 *                     "2020-01"                  → 2  (month)
 *                     "2020-01-15"               → 3  (day)
 *                     "2020-01-15T10"            → 4  (hour)
 *                     "2020-01-15T10:30"         → 5  (minute)
 *                     "2020-01-15T10:30:00"      → 6  (second)
 *                     "2020-01-15T10:30:00.000"  → 7  (millisecond)
 *  - Anything else: empty
 *
 * @author Ardenexal <https://github.com/Ardenexal>
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

        if (is_float($value)) {
            return Collection::single($this->decimalPrecision($value));
        }

        if (is_int($value)) {
            return Collection::single(0);
        }

        if (is_string($value)) {
            $datePrecision = $this->dateTimePrecision($value);
            if ($datePrecision !== null) {
                return Collection::single($datePrecision);
            }
        }

        return Collection::empty();
    }

    /**
     * Count digits after the decimal point in a float.
     */
    private function decimalPrecision(float $value): int
    {
        $str = (string) $value;

        $dotPos = strpos($str, '.');
        if ($dotPos === false) {
            return 0;
        }

        // Strip scientific notation suffix if present (e.g. "1.5E-5")
        $fractionalPart = substr($str, $dotPos + 1);
        $ePos           = stripos($fractionalPart, 'e');
        if ($ePos !== false) {
            $fractionalPart = substr($fractionalPart, 0, $ePos);
        }

        return strlen($fractionalPart);
    }

    /**
     * Determine the precision level of an ISO 8601 date/datetime string.
     * Returns null when the string does not look like a date.
     */
    private function dateTimePrecision(string $value): ?int
    {
        // Match ISO 8601 date/datetime patterns and count components
        // Patterns (from least to most precise):
        //   YYYY                    → 1
        //   YYYY-MM                 → 2
        //   YYYY-MM-DD              → 3
        //   YYYY-MM-DDTHH           → 4
        //   YYYY-MM-DDTHH:MM        → 5
        //   YYYY-MM-DDTHH:MM:SS     → 6
        //   YYYY-MM-DDTHH:MM:SS.mmm → 7

        $pattern = '/^\d{4}(-\d{2}(-\d{2}(T\d{2}(:\d{2}(:\d{2}(\.\d+)?)?)?)?)?)?([+-]\d{2}:\d{2}|Z)?$/';

        if (!preg_match($pattern, $value)) {
            return null;
        }

        // Strip timezone suffix for length analysis
        $stripped = preg_replace('/([+-]\d{2}:\d{2}|Z)$/', '', $value) ?? $value;

        return match (true) {
            str_contains($stripped, '.')                                     => 7,
            preg_match('/T\d{2}:\d{2}:\d{2}$/', $stripped) === 1             => 6,
            preg_match('/T\d{2}:\d{2}$/', $stripped)       === 1             => 5,
            preg_match('/T\d{2}$/', $stripped)             === 1             => 4,
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $stripped) === 1             => 3,
            preg_match('/^\d{4}-\d{2}$/', $stripped)       === 1             => 2,
            preg_match('/^\d{4}$/', $stripped)             === 1             => 1,
            default                                                          => null,
        };
    }
}
