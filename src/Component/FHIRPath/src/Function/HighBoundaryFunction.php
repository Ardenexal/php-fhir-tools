<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath highBoundary() function.
 *
 * Returns the highest possible value that rounds to the given decimal at the
 * specified precision, or the end of the period implied by a date/time string.
 *
 * Signature: highBoundary([precision : Integer]) : Decimal | DateTime
 *
 * For decimals:
 *   1.8      → 1.85   (precision inferred as 1 digit → half-unit = 0.05)
 *   1.58     → 1.585  (precision inferred as 2 digits → half-unit = 0.005)
 *
 * For date/time strings:
 *   "2020"       → "2020-12-31T23:59:59.999"
 *   "2020-03"    → "2020-03-31T23:59:59.999"   (last day of that month)
 *   "2020-03-15" → "2020-03-15T23:59:59.999"
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class HighBoundaryFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('highBoundary');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        // Resolve optional precision parameter
        $precision = null;
        if (count($parameters) === 1) {
            $evaluator = $context->getEvaluator();
            if ($evaluator === null) {
                throw new EvaluationException('Evaluator not available in context');
            }

            $precResult = $evaluator->evaluate($parameters[0], $context);
            if (!$precResult->isEmpty()) {
                $precValue = $precResult->first();
                if (is_int($precValue)) {
                    $precision = $precValue;
                }
            }
        }

        $value = $input->first();

        if (is_float($value) || is_int($value)) {
            return Collection::single($this->decimalHighBoundary((float) $value, $precision));
        }

        if (is_string($value)) {
            $result = $this->dateTimeHighBoundary($value);
            if ($result !== null) {
                return Collection::single($result);
            }
        }

        return Collection::empty();
    }

    /**
     * Compute the high boundary for a decimal value.
     *
     * If $precision is given, use that many decimal places; otherwise infer
     * from the string representation of the value.
     */
    private function decimalHighBoundary(float $value, ?int $precision): float
    {
        if ($precision === null) {
            $str       = rtrim(rtrim((string) $value, '0'), '.');
            $dotPos    = strpos($str, '.');
            $precision = $dotPos !== false ? strlen($str) - $dotPos - 1 : 0;
        }

        // Half a unit at the last significant digit
        $halfUnit = 0.5 * (10 ** (-$precision));

        return round($value + $halfUnit, $precision + 1);
    }

    /**
     * Compute the high boundary (end of period) for a date/time string.
     *
     * Returns the expanded ISO 8601 datetime string with components filled in
     * at their maximum values, or null when the string is not recognised.
     */
    private function dateTimeHighBoundary(string $value): ?string
    {
        // Quick sanity check: must start with 4 digits.
        if (!preg_match('/^\d{4}/', $value)) {
            return null;
        }

        // Strip timezone suffix before parsing positional components.
        $stripped = (string) preg_replace('/([+-]\d{2}:\d{2}|Z)$/', '', $value);

        $year = substr($stripped, 0, 4);

        $month  = strlen($stripped) >= 7 ? substr($stripped, 5, 2) : null;
        $day    = strlen($stripped) >= 10 ? substr($stripped, 8, 2) : null;
        $hour   = strlen($stripped) >= 13 ? substr($stripped, 11, 2) : null;
        $minute = strlen($stripped) >= 16 ? substr($stripped, 14, 2) : null;
        $second = strlen($stripped) >= 19 ? substr($stripped, 17, 2) : null;

        // Fill maximal values for unspecified components.
        $resolvedMonth  = $month  ?? '12';
        $resolvedDay    = $day    ?? (string) $this->lastDayOfMonth((int) $year, (int) $resolvedMonth);
        $resolvedHour   = $hour   ?? '23';
        $resolvedMinute = $minute ?? '59';
        $resolvedSecond = $second ?? '59';

        return "{$year}-{$resolvedMonth}-{$resolvedDay}T{$resolvedHour}:{$resolvedMinute}:{$resolvedSecond}.999";
    }

    /**
     * Return the last day of the given year-month combination.
     */
    private function lastDayOfMonth(int $year, int $month): int
    {
        $ts = mktime(0, 0, 0, $month, 1, $year);

        return $ts !== false ? (int) date('t', $ts) : 31;
    }
}
