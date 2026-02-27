<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;

/**
 * FHIRPath lowBoundary() function.
 *
 * Returns the lowest possible value in the natural range expressed by the input.
 *
 * Signature: lowBoundary([precision : Integer]) : Decimal | Date | DateTime | Time
 *
 * For decimals / integers:
 *   lowBoundary = value - (0.5 × 10^-sourcePrecision)
 *   Formatted to outputPrecision decimal places (default 8), floored toward -∞.
 *   Result is returned as FHIRPathDecimal to preserve trailing zeros.
 *
 * For date/time:
 *   Fills unspecified components with their minimum values.
 *   When no timezone is present and the output includes a time component, +14:00
 *   is appended (widest positive UTC offset = earliest absolute moment).
 *   The precision parameter controls the output granularity using positional numbers:
 *     Date:     YYYY=4, YYYY-MM=6, YYYY-MM-DD=8
 *     DateTime: ...THH=10, ...THH:MM=12, ...THH:MM:SS=14, ...THH:MM:SS.mmm=17
 *     Time:     THH=2, THH:MM=4, THH:MM:SS=6, THH:MM:SS.mmm=9
 */
final class LowBoundaryFunction extends AbstractFunction
{
    /** Default output decimal places when no precision parameter is given */
    private const DEFAULT_DECIMAL_PRECISION = 8;

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

        // --- Decimal / integer input ---
        if ($value instanceof FHIRPathDecimal || is_float($value) || is_int($value)) {
            // Validate precision range per FHIRPath spec (0-28)
            if ($precision !== null && ($precision < 0 || $precision > 28)) {
                return Collection::empty();
            }

            return Collection::single($this->decimalLowBoundary($value, $precision));
        }

        // --- Temporal literal input ---
        if ($value instanceof FHIRPathTemporalTypeInterface) {
            $result = $this->temporalLowBoundary($value->getValue(), $value->getTemporalTypeName(), $precision);

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        // --- Plain string fallback (resource property values) ---
        if (is_string($value)) {
            $bare = ltrim($value, '@');
            if (str_starts_with($bare, 'T')) {
                $result = $this->timeLowBoundary($bare, $precision);
            } else {
                $result = $this->dateTimeLowBoundary($bare, $precision);
            }

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        return Collection::empty();
    }

    // -------------------------------------------------------------------------
    // Decimal helpers
    // -------------------------------------------------------------------------

    /**
     * Compute the low boundary for a decimal/integer value using bcmath.
     *
     * Returns a FHIRPathDecimal with the result formatted to outputPrecision
     * decimal places (including trailing zeros).
     */
    private function decimalLowBoundary(
        FHIRPathDecimal|float|int $value,
        ?int $outputPrecision,
    ): FHIRPathDecimal {
        [$valueStr, $sourcePrecision] = $this->extractDecimalParts($value);
        $outPrec = $outputPrecision ?? self::DEFAULT_DECIMAL_PRECISION;

        // half = 0.5 × 10^(-sourcePrecision) as an exact decimal string
        $half = '0.' . str_repeat('0', $sourcePrecision) . '5';
        assert(is_numeric($half));

        $workScale = max($outPrec, $sourcePrecision + 1) + 2;
        $raw       = bcsub($valueStr, $half, $workScale);
        $result    = $this->bcFloor($raw, $outPrec);

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

        // float — use PHP string representation
        $str    = (string) $value;
        $dotPos = strpos($str, '.');
        $prec   = $dotPos !== false ? strlen($str) - $dotPos - 1 : 0;

        return [$str, $prec];
    }

    /**
     * Truncate a bcmath numeric string toward -∞ at the given scale.
     *
     * bcmath's bcadd($v, '0', $scale) truncates toward zero. For negative
     * values that lose magnitude in truncation we step down by one ULP.
     *
     * @param numeric-string $value
     */
    private function bcFloor(string $value, int $scale): string
    {
        $truncated = bcadd($value, '0', $scale);

        // If value < truncated (truncation went toward +∞), subtract 1 ULP
        if (bccomp($value, $truncated, 20) < 0) {
            $step = $scale === 0 ? '1' : '0.' . str_repeat('0', $scale - 1) . '1';
            assert(is_numeric($step));
            $truncated = bcsub($truncated, $step, $scale);
        }

        return $truncated;
    }

    // -------------------------------------------------------------------------
    // Temporal helpers
    // -------------------------------------------------------------------------

    /**
     * Dispatch to the correct temporal low-boundary method.
     */
    private function temporalLowBoundary(string $value, string $typeName, ?int $precision): ?string
    {
        if ($typeName === 'time') {
            return $this->timeLowBoundary($value, $precision);
        }

        return $this->dateTimeLowBoundary($value, $precision);
    }

    /**
     * Compute the low boundary for a date or datetime string.
     *
     * The value has already had the @ prefix stripped by the temporal value object.
     * Precision uses positional numbering: 4=year, 6=month, 8=day,
     * 10=hour, 12=minute, 14=second, 17=millisecond.
     */
    private function dateTimeLowBoundary(string $value, ?int $precision): ?string
    {
        // Must start with 4 digits (year)
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
        $year   = substr($value, 0, 4);
        $month  = strlen($value) >= 7 ? substr($value, 5, 2) : null;
        $day    = strlen($value) >= 10 ? substr($value, 8, 2) : null;

        // Detect time portion — bare 'T08' is normalised to 'T08:00'
        $hour   = null;
        $minute = null;
        $second = null;
        $milli  = null;

        $tPos = strpos($value, 'T');
        if ($tPos !== false) {
            $timePart = substr($value, $tPos + 1); // e.g. "08", "08:05", "08:05:30"

            $timeParts = explode(':', $timePart);
            $hour      = $timeParts[0];
            $minute    = $timeParts[1] ?? null; // null when bare 'THH'

            if (isset($timeParts[2])) {
                $secParts = explode('.', $timeParts[2]);
                $second   = $secParts[0];
                $milli    = isset($secParts[1])
                    ? str_pad(substr($secParts[1], 0, 3), 3, '0')
                    : null;
            }
        }

        // Default output precision: expand to millisecond
        $outPrec = $precision ?? 17;

        if ($outPrec < 4 || $outPrec > 17) {
            return null;
        }

        // Build output — fill unspecified components with low-boundary (minimum) values
        $outYear   = $year;
        $outMonth  = $month  ?? '01';
        $outDay    = $day    ?? '01';
        $outHour   = $hour   ?? '00';
        // 'THH' normalised to 'THH:00' — if minute was absent set it to '00'
        $outMinute = $minute ?? '00';
        $outSecond = $second ?? '00';
        $outMilli  = $milli  ?? '000';

        return match (true) {
            $outPrec <= 4  => "@{$outYear}",
            $outPrec <= 6  => "@{$outYear}-{$outMonth}",
            $outPrec <= 8  => "@{$outYear}-{$outMonth}-{$outDay}",
            $outPrec <= 10 => "@{$outYear}-{$outMonth}-{$outDay}T{$outHour}",
            $outPrec <= 12 => "@{$outYear}-{$outMonth}-{$outDay}T{$outHour}:{$outMinute}",
            $outPrec <= 14 => "@{$outYear}-{$outMonth}-{$outDay}T{$outHour}:{$outMinute}:{$outSecond}",
            // Millisecond precision: append timezone (existing or +14:00 for widest low bound)
            default        => "@{$outYear}-{$outMonth}-{$outDay}T{$outHour}:{$outMinute}:{$outSecond}.{$outMilli}"
                . ($timezone ?? '+14:00'),
        };
    }

    /**
     * Compute the low boundary for a time-only literal (@T...).
     *
     * Precision uses positional numbering: 2=hour, 4=minute, 6=second, 9=millisecond.
     */
    private function timeLowBoundary(string $value, ?int $precision): ?string
    {
        // Strip leading T
        $t = str_starts_with($value, 'T') ? substr($value, 1) : $value;

        $parts  = explode(':', $t);
        $hour   = $parts[0];
        $minute = $parts[1] ?? null;
        $second = null;
        $milli  = null;

        if (isset($parts[2])) {
            $secParts = explode('.', $parts[2]);
            $second   = $secParts[0];
            $milli    = isset($secParts[1])
                ? str_pad(substr($secParts[1], 0, 3), 3, '0')
                : null;
        }

        $outPrec = $precision ?? 9;

        if ($outPrec < 2 || $outPrec > 9) {
            return null;
        }

        $outMinute = $minute ?? '00';
        $outSecond = $second ?? '00';
        $outMilli  = $milli  ?? '000';

        return match (true) {
            $outPrec <= 2 => "@T{$hour}",
            $outPrec <= 4 => "@T{$hour}:{$outMinute}",
            $outPrec <= 6 => "@T{$hour}:{$outMinute}:{$outSecond}",
            default       => "@T{$hour}:{$outMinute}:{$outSecond}.{$outMilli}",
        };
    }
}
