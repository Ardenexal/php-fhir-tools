<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath lowBoundary() function.
 *
 * Returns the lowest possible value that rounds to the given decimal at the
 * specified precision, or the start of the period implied by a date/time string.
 *
 * Signature: lowBoundary([precision : Integer]) : Decimal | DateTime
 *
 * For decimals:
 *   1.8      → 1.75   (precision inferred as 1 digit → half-unit = 0.05)
 *   1.58     → 1.575  (precision inferred as 2 digits → half-unit = 0.005)
 *
 * For date/time strings:
 *   "2020"          → "2020-01-01T00:00:00.000"
 *   "2020-03"       → "2020-03-01T00:00:00.000"
 *   "2020-03-15"    → "2020-03-15T00:00:00.000"
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class LowBoundaryFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('lowBoundary');
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
            // Validate precision range per FHIRPath spec (0-31)
            if ($precision !== null && ($precision < 0 || $precision > 31)) {
                return Collection::empty();
            }

            return Collection::single($this->decimalLowBoundary((float) $value, $precision));
        }

        if (is_string($value)) {
            $result = $this->dateTimeLowBoundary($value, $precision);
            if ($result !== null) {
                return Collection::single($result);
            }
        }

        return Collection::empty();
    }

    /**
     * Compute the low boundary for a decimal value.
     *
     * If $precision is given, use that many decimal places; otherwise infer
     * from the string representation of the value.
     */
    private function decimalLowBoundary(float $value, ?int $precision): float
    {
        if ($precision === null) {
            $str       = rtrim(rtrim((string) $value, '0'), '.');
            $dotPos    = strpos($str, '.');
            $precision = $dotPos !== false ? strlen($str) - $dotPos - 1 : 0;
        }

        // Half a unit at the last significant digit
        $halfUnit = 0.5 * (10 ** (-$precision));

        return round($value - $halfUnit, $precision + 1);
    }

    /**
     * Compute the low boundary (start of period) for a date/time string.
     *
     * Returns the expanded ISO 8601 datetime string with components filled in
     * at their minimum values, or null when the string is not recognised.
     *
     * @param string   $value     The datetime literal string
     * @param int|null $precision Optional precision level (4=year, 6=month, 8=day, etc.)
     */
    private function dateTimeLowBoundary(string $value, ?int $precision): ?string
    {
        // Handle @-prefixed datetime literals
        $stripped = ltrim($value, '@');

        // Quick sanity check: must start with 4 digits or T for time literals
        if (!preg_match('/^(T|\d{4})/', $stripped)) {
            return null;
        }

        // Handle time-only literals (@T...)
        if (str_starts_with($stripped, 'T')) {
            return $this->timeLowBoundary($stripped, $precision);
        }

        // Strip timezone suffix before parsing positional components
        $tzMatch = [];
        if (preg_match('/([+-]\d{2}:\d{2}|Z)$/', $stripped, $tzMatch)) {
            $timezone = $tzMatch[1];
            $stripped = substr($stripped, 0, -strlen($timezone));
        } else {
            $timezone = null;
        }

        // Parse components based on current precision
        $year   = substr($stripped, 0, 4);
        $month  = strlen($stripped) >= 7 ? substr($stripped, 5, 2) : '01';
        $day    = strlen($stripped) >= 10 ? substr($stripped, 8, 2) : '01';
        $hour   = strlen($stripped) >= 13 ? substr($stripped, 11, 2) : '00';
        $minute = strlen($stripped) >= 16 ? substr($stripped, 14, 2) : '00';
        $second = strlen($stripped) >= 19 ? substr($stripped, 17, 2) : '00';

        $dotPos = strpos($stripped, '.');
        if ($dotPos !== false) {
            $rawMilli = substr($stripped, $dotPos + 1);
            $milli    = strlen($rawMilli) >= 3 ? substr($rawMilli, 0, 3) : str_pad($rawMilli, 3, '0');
        } else {
            $milli = '000';
        }

        // If precision is specified, return the appropriate format
        if ($precision !== null) {
            if ($precision < 4 || $precision > 17) {
                return null; // Invalid precision for datetime
            }

            return match (true) {
                $precision <= 4  => "@{$year}",
                $precision <= 6  => "@{$year}-{$month}",
                $precision <= 8  => "@{$year}-{$month}-{$day}",
                $precision <= 10 => "@{$year}-{$month}-{$day}T{$hour}",
                $precision <= 12 => "@{$year}-{$month}-{$day}T{$hour}:{$minute}",
                $precision <= 14 => "@{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}",
                default          => "@{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$milli}" . ($timezone ?? ''),
            };
        }

        // Default: return full datetime with timezone if present
        $result = "@{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$milli}";
        if ($timezone !== null) {
            $result .= $timezone;
        }

        return $result;
    }

    /**
     * Compute the low boundary for a time literal (@T...).
     */
    private function timeLowBoundary(string $value, ?int $precision): ?string
    {
        // Strip the T prefix
        $stripped = substr($value, 1);

        // Parse time components
        $parts = explode(':', $stripped);
        $hour  = $parts[0];
        $min   = $parts[1] ?? '00';
        $sec   = '00';
        $milli = '000';

        if (isset($parts[2])) {
            $secParts = explode('.', $parts[2]);
            $sec      = $secParts[0];
            $milli    = isset($secParts[1]) ? str_pad(substr($secParts[1], 0, 3), 3, '0') : '000';
        }

        // If precision specified, format accordingly
        if ($precision !== null) {
            if ($precision < 9 || $precision > 17) {
                return null; // Invalid precision for time
            }

            return match (true) {
                $precision <= 9  => "@T{$hour}",
                $precision <= 11 => "@T{$hour}:{$min}",
                $precision <= 13 => "@T{$hour}:{$min}:{$sec}",
                default          => "@T{$hour}:{$min}:{$sec}.{$milli}",
            };
        }

        return "@T{$hour}:{$min}:{$sec}.{$milli}";
    }
}
