<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;

/**
 * FHIRPath highBoundary() function.
 *
 * Returns the highest possible value in the natural range expressed by the input.
 *
 * Signature: highBoundary([precision : Integer]) : Decimal | Date | DateTime | Time
 *
 * For decimals / integers:
 *   highBoundary = value + (0.5 × 10^-sourcePrecision)
 *   Formatted to outputPrecision decimal places (default 8), ceiled toward +∞.
 *   Result is returned as FHIRPathDecimal to preserve trailing zeros.
 *
 * For date/time:
 *   Fills unspecified components with their maximum values.
 *   When no timezone is present and the output includes a time component, -12:00
 *   is appended (widest negative UTC offset = latest absolute moment).
 *   Precision uses positional numbers identical to lowBoundary().
 */
final class HighBoundaryFunction extends AbstractFunction
{
    /** Default output decimal places when no precision parameter is given */
    private const DEFAULT_DECIMAL_PRECISION = 8;

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

        // --- Decimal / integer input ---
        if ($value instanceof FHIRPathDecimal || is_float($value) || is_int($value)) {
            // Validate precision range per FHIRPath spec (0-28)
            if ($precision !== null && ($precision < 0 || $precision > 28)) {
                return Collection::empty();
            }

            return Collection::single($this->decimalHighBoundary($value, $precision));
        }

        // --- Temporal literal input ---
        if ($value instanceof FHIRPathTemporalTypeInterface) {
            $result = $this->temporalHighBoundary($value->getValue(), $value->getTemporalTypeName(), $precision);

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        // --- Plain string fallback (resource property values) ---
        if (is_string($value)) {
            $bare = ltrim($value, '@');
            if (str_starts_with($bare, 'T')) {
                $result = $this->timeHighBoundary($bare, $precision);
            } else {
                $result = $this->dateTimeHighBoundary($bare, $precision);
            }

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        return Collection::empty();
    }

    // -------------------------------------------------------------------------
    // Decimal helpers
    // -------------------------------------------------------------------------

    /**
     * Compute the high boundary for a decimal/integer value using bcmath.
     */
    private function decimalHighBoundary(
        FHIRPathDecimal|float|int $value,
        ?int $outputPrecision,
    ): FHIRPathDecimal {
        [$valueStr, $sourcePrecision] = $this->extractDecimalParts($value);
        $outPrec = $outputPrecision ?? self::DEFAULT_DECIMAL_PRECISION;

        $half = '0.' . str_repeat('0', $sourcePrecision) . '5';
        assert(is_numeric($half));

        $workScale = max($outPrec, $sourcePrecision + 1) + 2;
        $raw       = bcadd($valueStr, $half, $workScale);
        $result    = $this->bcCeil($raw, $outPrec);

        return new FHIRPathDecimal($result);
    }

    /**
     * Extract the bcmath-compatible string and source precision from a value.
     *
     * @return array{numeric-string, int}
     */
    private function extractDecimalParts(FHIRPathDecimal|float|int $value): array
    {
        if ($value instanceof FHIRPathDecimal) {
            $str = $value->value;
            assert(is_numeric($str));

            return [$str, $value->precision];
        }

        if (is_int($value)) {
            return [(string) $value, 0];
        }

        $str    = (string) $value;
        $dotPos = strpos($str, '.');
        $prec   = $dotPos !== false ? strlen($str) - $dotPos - 1 : 0;

        return [$str, $prec];
    }

    /**
     * Truncate a bcmath numeric string toward +∞ at the given scale.
     *
     * @param numeric-string $value
     */
    private function bcCeil(string $value, int $scale): string
    {
        $truncated = bcadd($value, '0', $scale);

        // If value > truncated (truncation went toward -∞), add 1 ULP
        if (bccomp($value, $truncated, 20) > 0) {
            $step = $scale === 0 ? '1' : '0.' . str_repeat('0', $scale - 1) . '1';
            assert(is_numeric($step));
            $truncated = bcadd($truncated, $step, $scale);
        }

        return $truncated;
    }

    // -------------------------------------------------------------------------
    // Temporal helpers
    // -------------------------------------------------------------------------

    /**
     * Dispatch to the correct temporal high-boundary method.
     */
    private function temporalHighBoundary(string $value, string $typeName, ?int $precision): ?string
    {
        if ($typeName === 'time') {
            return $this->timeHighBoundary($value, $precision);
        }

        return $this->dateTimeHighBoundary($value, $precision);
    }

    /**
     * Compute the high boundary for a date or datetime string.
     *
     * Precision positional numbering: 4=year, 6=month, 8=day,
     * 10=hour, 12=minute, 14=second, 17=millisecond.
     */
    private function dateTimeHighBoundary(string $value, ?int $precision): ?string
    {
        if (!preg_match('/^\d{4}/', $value)) {
            return null;
        }

        // Extract timezone suffix
        $timezone = null;
        if (preg_match('/([+-]\d{2}:\d{2}|Z)$/', $value, $m)) {
            $timezone = $m[1];
            $value    = substr($value, 0, -strlen($timezone));
        }

        // Parse available components
        $year  = substr($value, 0, 4);
        $month = strlen($value) >= 7 ? substr($value, 5, 2) : null;
        $day   = strlen($value) >= 10 ? substr($value, 8, 2) : null;

        // Detect time portion — bare 'T08' is normalised to 'T08:00'
        $hour   = null;
        $minute = null;
        $second = null;

        $tPos = strpos($value, 'T');
        if ($tPos !== false) {
            $timePart  = substr($value, $tPos + 1);
            $timeParts = explode(':', $timePart);
            $hour      = $timeParts[0];
            $minute    = $timeParts[1] ?? null; // null for bare 'THH'

            if (isset($timeParts[2])) {
                $secParts = explode('.', $timeParts[2]);
                $second   = $secParts[0];
            }
        }

        $outPrec = $precision ?? 17;

        if ($outPrec < 4 || $outPrec > 17) {
            return null;
        }

        // Fill unspecified components with high-boundary (maximum) values.
        // Exception: minute defaults to '00' because the FHIRPath spec requires bare
        // 'THH' (e.g. T08) to be treated as 'THH:00' (T08:00) internally. Minute is
        // therefore a *known* value of 00 after normalisation, not an unspecified
        // component that should be maximised. The spec test confirms this:
        //   @2014-01-01T08.highBoundary(17) → @2014-01-01T08:00:59.999-12:00
        $resolvedMonth  = $month  ?? '12';
        $resolvedDay    = $day    ?? (string) $this->lastDayOfMonth((int) $year, (int) $resolvedMonth);
        $resolvedHour   = $hour   ?? '23';
        $resolvedMinute = $minute ?? '00'; // '00' by spec normalisation (THH → THH:00), not a maximum fill
        $resolvedSecond = $second ?? '59';

        return match (true) {
            $outPrec <= 4  => "@{$year}",
            $outPrec <= 6  => "@{$year}-{$resolvedMonth}",
            $outPrec <= 8  => "@{$year}-{$resolvedMonth}-{$resolvedDay}",
            $outPrec <= 10 => "@{$year}-{$resolvedMonth}-{$resolvedDay}T{$resolvedHour}",
            $outPrec <= 12 => "@{$year}-{$resolvedMonth}-{$resolvedDay}T{$resolvedHour}:{$resolvedMinute}",
            $outPrec <= 14 => "@{$year}-{$resolvedMonth}-{$resolvedDay}T{$resolvedHour}:{$resolvedMinute}:{$resolvedSecond}",
            // Millisecond: append timezone (existing or -12:00 for widest high bound)
            default        => "@{$year}-{$resolvedMonth}-{$resolvedDay}T{$resolvedHour}:{$resolvedMinute}:{$resolvedSecond}.999"
                . ($timezone ?? '-12:00'),
        };
    }

    /**
     * Compute the high boundary for a time-only literal (@T...).
     *
     * Precision positional numbering: 2=hour, 4=minute, 6=second, 9=millisecond.
     */
    private function timeHighBoundary(string $value, ?int $precision): ?string
    {
        // Strip leading T
        $t = str_starts_with($value, 'T') ? substr($value, 1) : $value;

        $parts  = explode(':', $t);
        $hour   = $parts[0];
        $minute = $parts[1] ?? null;
        $second = null;

        if (isset($parts[2])) {
            $second = explode('.', $parts[2])[0];
        }

        $outPrec = $precision ?? 9;

        if ($outPrec < 2 || $outPrec > 9) {
            return null;
        }

        $resolvedMinute = $minute ?? '00';
        $resolvedSecond = $second ?? '59';

        return match (true) {
            $outPrec <= 2 => "@T{$hour}",
            $outPrec <= 4 => "@T{$hour}:{$resolvedMinute}",
            $outPrec <= 6 => "@T{$hour}:{$resolvedMinute}:{$resolvedSecond}",
            default       => "@T{$hour}:{$resolvedMinute}:{$resolvedSecond}.999",
        };
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
