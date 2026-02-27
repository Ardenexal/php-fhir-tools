<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;

/**
 * Handles FHIRPath comparison operations with collection semantics and precision-aware temporal comparisons.
 *
 * Implements FHIRPath specification requirements:
 * - Collection equality uses set semantics (order-independent)
 * - Empty collections in comparisons return empty results
 * - DateTime values with different precisions are incomparable (return empty)
 * - Equivalence (~) applies type normalization, equality (=) preserves types
 */
final class ComparisonService
{
    private const UCUM_SYSTEM_URL = 'http://unitsofmeasure.org';

    private const FLOAT_TOLERANCE = 1.0E-9;

    private const YEAR_MONTH_CONVERSION = [
        'year'   => 12.0,
        'years'  => 12.0,
        'month'  => 1.0,
        'months' => 1.0,
    ];

    private const CALENDAR_DURATION_SECONDS = [
        'year'         => 365   * 24 * 60 * 60,
        'years'        => 365   * 24 * 60 * 60,
        'month'        => 30    * 24 * 60 * 60,
        'months'       => 30    * 24 * 60 * 60,
        'week'         => 7     * 24 * 60 * 60,
        'weeks'        => 7     * 24 * 60 * 60,
        'day'          => 24    * 60 * 60,
        'days'         => 24    * 60 * 60,
        'hour'         => 60    * 60,
        'hours'        => 60    * 60,
        'minute'       => 60,
        'minutes'      => 60,
        'second'       => 1,
        'seconds'      => 1,
        'millisecond'  => 0.001,
        'milliseconds' => 0.001,
    ];

    private const DURATION_ABOVE_WEEK = [
        'year'   => true,
        'years'  => true,
        'month'  => true,
        'months' => true,
    ];

    private const CALENDAR_TO_UCUM = [
        'year'         => 'a',
        'years'        => 'a',
        'month'        => 'mo',
        'months'       => 'mo',
        'week'         => 'wk',
        'weeks'        => 'wk',
        'day'          => 'd',
        'days'         => 'd',
        'hour'         => 'h',
        'hours'        => 'h',
        'minute'       => 'min',
        'minutes'      => 'min',
        'second'       => 's',
        'seconds'      => 's',
        'millisecond'  => 'ms',
        'milliseconds' => 'ms',
    ];

    private const UCUM_CONVERSIONS = [
        '1'       => ['base' => '1', 'factor' => 1.0],
        'kg'      => ['base' => 'kg', 'factor' => 1.0],
        'g'       => ['base' => 'kg', 'factor' => 0.001],
        'mg'      => ['base' => 'kg', 'factor' => 0.000001],
        '[lb_av]' => ['base' => 'kg', 'factor' => 0.45359237],
        'm'       => ['base' => 'm', 'factor' => 1.0],
        'cm'      => ['base' => 'm', 'factor' => 0.01],
        'mm'      => ['base' => 'm', 'factor' => 0.001],
        'km'      => ['base' => 'm', 'factor' => 1000.0],
        'L'       => ['base' => 'L', 'factor' => 1.0],
        'mL'      => ['base' => 'L', 'factor' => 0.001],
        'wk'      => ['base' => 'd', 'factor' => 7.0],
        'd'       => ['base' => 'd', 'factor' => 1.0],
    ];

    public function __construct(
        private readonly FHIRPathEvaluator $evaluator
    ) {
    }

    /**
     * Check if a value is a quantity and extract its components.
     *
     * Returns extracted quantity array or null if not a quantity.
     *
     * @return array{value: float, code: string, unit: string, system: string|null}|null
     */
    public function tryExtractQuantity(mixed $value): ?array
    {
        [$quantity, $isQuantity] = $this->extractQuantity($value);

        return $quantity;
    }

    /**
     * Evaluate equality/equivalence comparison operators.
     *
     * Operators: =, !=, ~, !~
     *
     * FHIRPath semantics:
     * - Empty collections return empty result
     * - Single-item collections compared by value
     * - Multi-item collections: = is positional (order-dependent), ~ is set-based (order-independent)
     * - DateTime values with different precisions are incomparable (return empty)
     *
     * @param string $operator One of: '=', '!=', '~', '!~'
     */
    public function compareEquality(Collection $left, Collection $right, string $operator): Collection
    {
        // Empty collections always return empty
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        $useEquivalence = in_array($operator, ['~', '!~'], true);
        $isNegated      = in_array($operator, ['!=', '!~'], true);

        // Get items from collections
        $leftItems  = $left->toArray();
        $rightItems = $right->toArray();

        // Compare collections
        $areEqual = $this->collectionsEqual($leftItems, $rightItems, $useEquivalence);

        // Handle incomparable case (null result from precision mismatch)
        if ($areEqual === null) {
            return Collection::empty();
        }

        // Apply negation if needed
        $result = $isNegated ? !$areEqual : $areEqual;

        return Collection::single($result);
    }

    /**
     * Evaluate ordering comparison operators.
     *
     * Operators: <, >, <=, >=
     *
     * FHIRPath semantics:
     * - Empty collections return empty result
     * - Multi-item collections return empty (ordering requires single values)
     * - Single values compared with precision awareness for DateTime
     *
     * @param callable(mixed, mixed): bool $operation
     */
    public function compareOrdering(Collection $left, Collection $right, callable $operation): Collection
    {
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        if (!$left->isSingle() || !$right->isSingle()) {
            return Collection::empty();
        }

        // Normalize values to handle FHIR primitives and enums
        $leftValue  = $this->evaluator->normalizeValue($left->first());
        $rightValue = $this->evaluator->normalizeValue($right->first());

        // Unwrap FHIRPath date/time literal wrappers to plain strings for ordering
        if ($leftValue instanceof FHIRPathTemporalTypeInterface) {
            $leftValue = $leftValue->getValue();
        }

        if ($rightValue instanceof FHIRPathTemporalTypeInterface) {
            $rightValue = $rightValue->getValue();
        }

        // FHIRPathDecimal ordering: use bccomp for precision-safe comparison
        $leftIsDecimal  = $leftValue instanceof FHIRPathDecimal;
        $rightIsDecimal = $rightValue instanceof FHIRPathDecimal;

        if ($leftIsDecimal || $rightIsDecimal) {
            $leftStr  = $leftIsDecimal  ? $leftValue->value  : (is_numeric($leftValue)  ? (string) $leftValue  : null);
            $rightStr = $rightIsDecimal ? $rightValue->value : (is_numeric($rightValue) ? (string) $rightValue : null);

            if ($leftStr === null || $rightStr === null) {
                return Collection::empty();
            }

            // Both strings are guaranteed numeric at this point
            assert(is_numeric($leftStr) && is_numeric($rightStr));

            $maxPrec = max(
                ($dotPos = strpos($leftStr, '.'))  !== false ? strlen($leftStr)  - $dotPos - 1 : 0,
                ($dotPos = strpos($rightStr, '.')) !== false ? strlen($rightStr) - $dotPos - 1 : 0
            );
            $cmp = bccomp($leftStr, $rightStr, $maxPrec + 2);

            return Collection::single($operation($cmp, 0));
        }

        [$leftQuantity, $leftIsQuantity]   = $this->extractQuantity($leftValue);
        [$rightQuantity, $rightIsQuantity] = $this->extractQuantity($rightValue);

        if ($leftIsQuantity || $rightIsQuantity) {
            if (!$leftIsQuantity || !$rightIsQuantity || $leftQuantity === null || $rightQuantity === null) {
                return Collection::empty();
            }

            $comparison = $this->compareQuantityValues($leftQuantity, $rightQuantity);
            if ($comparison === null) {
                return Collection::empty();
            }

            return Collection::single($operation($comparison, 0));
        }

        // DateTime: precision/timezone-aware ordering
        if ($this->isDateTimeString($leftValue) && $this->isDateTimeString($rightValue)) {
            $leftNorm  = $this->normalizeDateTimeString($leftValue);
            $rightNorm = $this->normalizeDateTimeString($rightValue);

            $leftPrecision  = $this->getDateTimePrecision($leftNorm);
            $rightPrecision = $this->getDateTimePrecision($rightNorm);

            if ($leftPrecision !== $rightPrecision) {
                $leftIsTimeOnly  = str_starts_with($leftNorm, 'T');
                $rightIsTimeOnly = str_starts_with($rightNorm, 'T');

                // Date/datetime vs time-only: incomparable types → empty
                if ($leftIsTimeOnly !== $rightIsTimeOnly) {
                    return Collection::empty();
                }

                $leftHasTime  = $this->hasTimeComponent($leftNorm);
                $rightHasTime = $this->hasTimeComponent($rightNorm);

                // Both have time components (datetime vs datetime different precision) → ambiguous → empty
                if ($leftHasTime && $rightHasTime) {
                    return Collection::empty();
                }

                // One is date-only, the other is datetime: truncate to date precision.
                // If equal at that precision the comparison is ambiguous → empty.
                // If clearly ordered at that precision the result is definitive.
                if ($leftPrecision === null || $rightPrecision === null) {
                    return Collection::empty();
                }

                $minPrecision = min($leftPrecision, $rightPrecision);
                $leftTrunc    = $this->truncateToMinPrecision($leftNorm, $minPrecision);
                $rightTrunc   = $this->truncateToMinPrecision($rightNorm, $minPrecision);

                if ($leftTrunc === $rightTrunc) {
                    return Collection::empty();
                }

                return Collection::single($operation($leftTrunc, $rightTrunc));
            }

            $leftHasTz  = $this->hasTimezone($leftNorm);
            $rightHasTz = $this->hasTimezone($rightNorm);

            if ($leftHasTz !== $rightHasTz) {
                return Collection::empty();
            }

            if ($leftHasTz) {
                // Both have timezone: compare as UTC timestamps
                $leftUtc  = $this->toUtcTimestamp($leftNorm);
                $rightUtc = $this->toUtcTimestamp($rightNorm);

                if ($leftUtc === null || $rightUtc === null) {
                    return Collection::empty();
                }

                return Collection::single($operation($leftUtc, $rightUtc));
            }

            $leftValue  = $leftNorm;
            $rightValue = $rightNorm;
        }

        // Operands must be the same primitive type; mixed-type ordering is an error
        if (is_numeric($leftValue) !== is_numeric($rightValue)) {
            throw new EvaluationException('Ordering comparison requires operands of the same type');
        }

        $result = $operation($leftValue, $rightValue);

        return Collection::single($result);
    }

    /**
     * Compare two collections for equality or equivalence.
     *
     * Per the FHIRPath spec:
     * - Equality (`=`): positional, order-dependent — each item at position i must
     *   equal the item at position i in the other collection.
     * - Equivalence (`~`): set semantics, order-independent — each item must have
     *   a matching item somewhere in the other collection.
     *
     * @param array<mixed> $left
     * @param array<mixed> $right
     *
     * @return bool|null True if equal/equivalent, false if not, null if incomparable
     */
    private function collectionsEqual(array $left, array $right, bool $useEquivalence): ?bool
    {
        // In equivalence mode, normalize collections by removing equivalent duplicates
        if ($useEquivalence) {
            $left  = $this->normalizeCollectionEquivalence($left);
            $right = $this->normalizeCollectionEquivalence($right);
        }

        // Different sizes cannot be equal
        if (count($left) !== count($right)) {
            return false;
        }

        // Empty collections are equal
        if (count($left) === 0) {
            return true;
        }

        if (!$useEquivalence) {
            // Equality mode: positional (order-dependent) comparison
            $rightValues = array_values($right);

            foreach (array_values($left) as $i => $leftItem) {
                $isEqual = $this->valuesEqual($leftItem, $rightValues[$i], false);

                if ($isEqual === null) {
                    return null;
                }

                if (!$isEqual) {
                    return false;
                }
            }

            return true;
        }

        // Equivalence mode: set matching (order-independent)
        // Track which right items have been matched to prevent double-matching
        $matchedIndices = [];

        foreach ($left as $leftItem) {
            $foundMatch = false;

            foreach ($right as $rightIndex => $rightItem) {
                if (in_array($rightIndex, $matchedIndices, true)) {
                    continue;
                }

                $isEqual = $this->valuesEqual($leftItem, $rightItem, true);

                if ($isEqual === null) {
                    return null;
                }

                if ($isEqual === true) {
                    $matchedIndices[] = $rightIndex;
                    $foundMatch       = true;
                    break;
                }
            }

            if (!$foundMatch) {
                return false;
            }
        }

        return true;
    }

    /**
     * Normalize a collection by removing equivalent duplicates.
     *
     * In equivalence mode, values like 1 and 1.0 are considered the same,
     * so [1, 1.0, 2] becomes [1, 2].
     *
     * @param array<mixed> $items
     *
     * @return array<mixed>
     */
    private function normalizeCollectionEquivalence(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            $isDuplicate = false;

            foreach ($normalized as $existing) {
                $isEqual = $this->valuesEqual($item, $existing, true);

                // If equivalent, it's a duplicate
                if ($isEqual === true) {
                    $isDuplicate = true;
                    break;
                }
            }

            if (!$isDuplicate) {
                $normalized[] = $item;
            }
        }

        return $normalized;
    }

    /**
     * Compare two values for equality.
     *
     * @return bool|null True if equal, false if not equal, null if incomparable (different precisions)
     */
    private function valuesEqual(mixed $a, mixed $b, bool $useEquivalence): ?bool
    {
        // Normalize FHIR primitives and enums to PHP scalars
        $a = $this->evaluator->normalizeValue($a);
        $b = $this->evaluator->normalizeValue($b);

        // Unwrap FHIRPath date/time literal wrappers to plain strings for comparison
        if ($a instanceof FHIRPathTemporalTypeInterface) {
            $a = $a->getValue();
        }

        if ($b instanceof FHIRPathTemporalTypeInterface) {
            $b = $b->getValue();
        }

        // FHIRPathDecimal comparison: use bccomp for precision-safe equality
        $aIsDecimal = $a instanceof FHIRPathDecimal;
        $bIsDecimal = $b instanceof FHIRPathDecimal;

        if ($aIsDecimal || $bIsDecimal) {
            $aStr = $aIsDecimal ? $a->value : (is_numeric($a) ? (string) $a : null);
            $bStr = $bIsDecimal ? $b->value : (is_numeric($b) ? (string) $b : null);

            if ($aStr === null || $bStr === null) {
                return false;
            }

            // Both strings are guaranteed numeric at this point
            assert(is_numeric($aStr) && is_numeric($bStr));

            if ($useEquivalence) {
                // Equivalence: round both values to the least-precise operand's scale.
                // bcadd with a lower scale truncates; we need half-up rounding instead.
                $precA   = ($dotPos = strpos($aStr, '.')) !== false ? strlen($aStr) - $dotPos - 1 : 0;
                $precB   = ($dotPos = strpos($bStr, '.')) !== false ? strlen($bStr) - $dotPos - 1 : 0;
                $minPrec = min($precA, $precB);

                return bccomp(
                    $this->bcRoundHalfUp($aStr, $minPrec),
                    $this->bcRoundHalfUp($bStr, $minPrec),
                    $minPrec
                ) === 0;
            }

            // Equality: compare at max precision
            $maxPrec = max(
                ($dotPos = strpos($aStr, '.')) !== false ? strlen($aStr) - $dotPos - 1 : 0,
                ($dotPos = strpos($bStr, '.')) !== false ? strlen($bStr) - $dotPos - 1 : 0
            );

            return bccomp($aStr, $bStr, $maxPrec + 2) === 0;
        }

        [$leftQuantity, $leftIsQuantity]   = $this->extractQuantity($a);
        [$rightQuantity, $rightIsQuantity] = $this->extractQuantity($b);

        if ($leftIsQuantity || $rightIsQuantity) {
            if (!$leftIsQuantity || !$rightIsQuantity || $leftQuantity === null || $rightQuantity === null) {
                return null;
            }

            $comparison = $this->compareQuantityValues($leftQuantity, $rightQuantity);
            if ($comparison === null) {
                return null;
            }

            // For equivalence (~), use relative tolerance (~10% per FHIRPath spec)
            // For equality (=), use strict absolute tolerance
            if ($useEquivalence) {
                $leftValue  = $leftQuantity['value'];
                $rightValue = $rightQuantity['value'];
                $maxValue   = max(abs($leftValue), abs($rightValue));

                // Use 10% relative tolerance, or absolute tolerance for very small values
                if ($maxValue > 0.0) {
                    return abs($comparison) <= ($maxValue * 0.10);
                }

                return abs($comparison) <= self::FLOAT_TOLERANCE;
            }

            return abs($comparison) <= self::FLOAT_TOLERANCE;
        }

        // DateTime precision-aware comparison
        if ($this->isDateTimeString($a) && $this->isDateTimeString($b)) {
            return $this->compareDateTimeEquality($a, $b, $useEquivalence);
        }

        // For equivalence mode: apply type normalization
        if ($useEquivalence) {
            // String equivalence: case-insensitive per FHIRPath spec
            if (is_string($a) && is_string($b)) {
                return strcasecmp($a, $b) === 0;
            }

            // Numeric equivalence: 1 ~ 1.0 should be true
            if (is_numeric($a) && is_numeric($b)) {
                // Use loose comparison for numeric values
                return $a == $b;
            }

            // For other types, use loose comparison
            return $a == $b;
        }

        // For equality mode: strict type-preserving comparison with implicit numeric promotion.
        // FHIRPath spec: Integer is implicitly convertible to Decimal for comparisons,
        // so float(1.0) = int(1) must return true.
        if (is_float($a) && is_int($b)) {
            return $a === (float) $b;
        }

        if (is_int($a) && is_float($b)) {
            return (float) $a === $b;
        }

        return $a === $b;
    }

    /**
     * Round a bcmath numeric string to $scale decimal places using half-up rounding.
     *
     * bcadd($x, '0', $scale) truncates (floors toward zero), which produces incorrect
     * results for equivalence comparisons. This method adds half-a-ULP at the target
     * scale before truncating to get true half-up rounding.
     *
     * @param  numeric-string $value
     * @return numeric-string
     */
    private function bcRoundHalfUp(string $value, int $scale): string
    {
        if ($scale < 0) {
            return bcadd($value, '0', 0);
        }

        // half-a-unit at the target scale: "0.005" for scale=2, "0.05" for scale=1, etc.
        $half = '0.' . str_repeat('0', $scale) . '5';
        assert(is_numeric($half));

        if (str_starts_with(ltrim($value), '-')) {
            return bcsub($value, $half, $scale);
        }

        return bcadd($value, $half, $scale);
    }

    /**
     * Compare two date/time strings with precision and timezone awareness.
     *
     * FHIRPath spec rules for equality (=):
     * - Date-only vs DateTime (no T vs has T) → null (incomparable/unknown)
     * - DateTime vs DateTime with different sub-precision → false (definitively not equal)
     * - One has TZ, other doesn't → null (incomparable)
     * - Both have TZ → normalize to UTC for comparison
     *
     * For equivalence (~): any precision difference → false (never returns null)
     *
     * @return bool|null true=equal, false=not equal, null=incomparable
     */
    private function compareDateTimeEquality(string $a, string $b, bool $useEquivalence): ?bool
    {
        // Normalize zero-only fractional seconds before precision check
        $aNorm = $this->normalizeDateTimeString($a);
        $bNorm = $this->normalizeDateTimeString($b);

        $aPrecision = $this->getDateTimePrecision($aNorm);
        $bPrecision = $this->getDateTimePrecision($bNorm);

        if ($aPrecision === null || $bPrecision === null) {
            return $useEquivalence ? false : null;
        }

        if ($aPrecision !== $bPrecision) {
            if ($useEquivalence) {
                // Equivalence: any precision mismatch → not equivalent
                return false;
            }

            // Detect time-only strings (start with T, e.g. "T10:30:00")
            $aIsTimeOnly = str_starts_with($aNorm, 'T');
            $bIsTimeOnly = str_starts_with($bNorm, 'T');

            // Date/datetime vs time-only: fundamentally different types → not equal
            if ($aIsTimeOnly !== $bIsTimeOnly) {
                return false;
            }

            // Both time-only with different precision → incomparable (unknown)
            if ($aIsTimeOnly) {
                return null;
            }

            // Date-only vs datetime (one has T, other doesn't) → incomparable
            $aHasTime = $this->hasTimeComponent($aNorm);
            $bHasTime = $this->hasTimeComponent($bNorm);

            if ($aHasTime !== $bHasTime) {
                return null;
            }

            // Both date-only with different date precision → incomparable
            if (!$aHasTime) {
                return null;
            }

            // Both full datetimes (date+time): return false ONLY for seconds vs milliseconds
            // (precision 6 vs 7). The millisecond value is explicit and makes them definitively
            // unequal. All other datetime precision differences are incomparable.
            $minPrec = min($aPrecision, $bPrecision);
            $maxPrec = max($aPrecision, $bPrecision);

            if ($minPrec === 6 && $maxPrec === 7) {
                return false;
            }

            // Other datetime precision mismatches (e.g. hours vs minutes) → incomparable
            return null;
        }

        // Same precision: check timezone compatibility
        $aHasTz = $this->hasTimezone($aNorm);
        $bHasTz = $this->hasTimezone($bNorm);

        if ($aHasTz !== $bHasTz) {
            // One has explicit TZ, other doesn't → unknown what offset the other uses
            return $useEquivalence ? false : null;
        }

        if ($aHasTz) {
            // Both have timezone: normalize to UTC for comparison
            $aUtc = $this->toUtcTimestamp($aNorm);
            $bUtc = $this->toUtcTimestamp($bNorm);

            if ($aUtc === null || $bUtc === null) {
                return $useEquivalence ? false : null;
            }

            return abs($aUtc - $bUtc) < 0.001;
        }

        // Same precision, no timezone: compare normalized strings
        return $aNorm === $bNorm;
    }

    /**
     * Detect whether a date/time string has an explicit timezone offset.
     *
     * Matches trailing Z (UTC) or +/-HH:MM offset.
     */
    private function hasTimezone(string $value): bool
    {
        return preg_match('/([+-]\d{2}:\d{2}|Z)$/', $value) === 1;
    }

    /**
     * Detect whether a date/time string has a time component (contains T).
     */
    private function hasTimeComponent(string $value): bool
    {
        return str_contains($value, 'T');
    }

    /**
     * Convert a datetime string with timezone info to a UTC floating-point timestamp.
     *
     * Returns null if the string cannot be parsed or has no timezone info.
     */
    private function toUtcTimestamp(string $value): ?float
    {
        // Strip any legacy @ prefix
        $value = ltrim($value, '@');

        try {
            $dt = new \DateTimeImmutable($value);

            return (float) $dt->format('U') + ((float) $dt->format('u')) / 1_000_000.0;
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Normalize a DateTime string for comparison.
     *
     * Strips @ prefix and normalizes zero-only fractional seconds.
     * Preserves timezone information.
     * E.g., "@T10:30:00.0" becomes "T10:30:00"
     * E.g., "@2018-03-01T10:30:00.000Z" becomes "2018-03-01T10:30:00Z"
     */
    private function normalizeDateTimeString(string $value): string
    {
        // Strip @ prefix
        $normalized = ltrim($value, '@');

        // Strip zero-only fractional seconds (.0, .00, .000)
        // Must preserve timezone, so we need to be careful
        // Pattern: fractional seconds are between the time part and optional timezone
        $normalized = preg_replace('/\.0+(?=([+-]\d{2}:\d{2}|Z)?$)/', '', $normalized) ?? $normalized;

        return $normalized;
    }

    /**
     * Detect if a value is a date/datetime/time string.
     *
     * Detects bare ISO date strings (YYYY-MM...) and time strings (THH:MM...).
     * Requires at least year+hyphen for dates to avoid false-positives on plain integers.
     * Also accepts legacy @ -prefixed literals for safety.
     */
    public function isDateTimeString(mixed $value): bool
    {
        // FHIRPath literal wrappers are always date/time values
        if ($value instanceof FHIRPathTemporalTypeInterface) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        // Bare ISO date: starts with YYYY- (e.g. "2020-01-15")
        // Bare time: starts with T + digits (e.g. "T10:30:00")
        // Legacy @ -prefixed (e.g. "@2020-01-15") accepted for safety
        return preg_match('/^(@?\d{4}-|@?T\d{2})/', $value) === 1;
    }

    /**
     * Extract precision level from a DateTime string.
     *
     * Precision levels (per FHIRPath spec):
     * 1 = year
     * 2 = month
     * 3 = day
     * 4 = hour
     * 5 = minute
     * 6 = second
     * 7 = millisecond
     *
     * @return int|null Precision level 1-7, or null if not a valid DateTime
     */
    public function getDateTimePrecision(string $value): ?int
    {
        // Strip @ prefix
        $value = ltrim($value, '@');

        // Main DateTime pattern with optional timezone
        $pattern = '/^\d{4}(-\d{2}(-\d{2}(T\d{2}(:\d{2}(:\d{2}(\.\d+)?)?)?)?)?)?([+-]\d{2}:\d{2}|Z)?$/';

        // Time-only pattern (e.g., @T10:30:00)
        $timePattern = '/^T\d{2}(:\d{2}(:\d{2}(\.\d+)?)?)?([+-]\d{2}:\d{2}|Z)?$/';

        if (!preg_match($pattern, $value) && !preg_match($timePattern, $value)) {
            return null;
        }

        // Strip timezone suffix for length analysis
        $stripped = preg_replace('/([+-]\d{2}:\d{2}|Z)$/', '', $value) ?? $value;

        // Check for non-zero fractional seconds
        // .0, .00, .000 are normalized to second precision (6)
        // .001, .123, etc. are millisecond precision (7)
        $hasNonZeroFractionalSeconds = false;
        if (str_contains($stripped, '.')) {
            if (preg_match('/\.(\d+)/', $stripped, $matches)) {
                // Check if fractional part contains any non-zero digit
                $hasNonZeroFractionalSeconds = !preg_match('/^0+$/', $matches[1]);

                // If all zeros, strip them from $stripped for subsequent pattern matching
                if (!$hasNonZeroFractionalSeconds) {
                    $stripped = preg_replace('/\.0+$/', '', $stripped) ?? $stripped;
                }
            }
        }

        return match (true) {
            $hasNonZeroFractionalSeconds                                     => 7,
            preg_match('/T\d{2}:\d{2}:\d{2}$/', $stripped) === 1             => 6,
            preg_match('/T\d{2}:\d{2}$/', $stripped)       === 1             => 5,
            preg_match('/T\d{2}$/', $stripped)             === 1             => 4,
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $stripped) === 1             => 3,
            preg_match('/^\d{4}-\d{2}$/', $stripped)       === 1             => 2,
            preg_match('/^\d{4}$/', $stripped)             === 1             => 1,
            default                                                          => null,
        };
    }

    /**
     * @return array{0: array{value: float, code: string, unit: string, system: string|null}|null, 1: bool}
     */
    private function extractQuantity(mixed $value): array
    {
        $value = $this->evaluator->normalizeValue($value);

        if (is_array($value)) {
            $isQuantity = array_key_exists('value', $value)
                && (array_key_exists('code', $value) || array_key_exists('unit', $value) || array_key_exists('system', $value));

            if (!$isQuantity) {
                return [null, false];
            }

            if (!is_numeric($value['value'])) {
                return [null, true];
            }

            $code   = is_string($value['code'] ?? null) ? $value['code'] : null;
            $unit   = is_string($value['unit'] ?? null) ? $value['unit'] : $code;
            $system = is_string($value['system'] ?? null) ? $value['system'] : null;

            if ($code === null || $code == '') {
                return [null, true];
            }

            if ($system !== null && $system !== self::UCUM_SYSTEM_URL) {
                return [null, true];
            }

            return [[
                'value'  => (float) $value['value'],
                'code'   => $code,
                'unit'   => $unit ?? $code,
                'system' => $system,
            ], true];
        }

        if (!is_object($value)) {
            return [null, false];
        }

        $hasValue  = $this->readObjectProperty($value, 'value', true);
        $hasCode   = $this->readObjectProperty($value, 'code', true);
        $hasUnit   = $this->readObjectProperty($value, 'unit', true);
        $hasSystem = $this->readObjectProperty($value, 'system', true);

        if (!$hasValue || (!$hasCode && !$hasUnit && !$hasSystem)) {
            return [null, false];
        }

        $rawValue = $this->readObjectProperty($value, 'value');
        if (!is_numeric($rawValue)) {
            return [null, true];
        }

        $codeValue   = $this->readObjectProperty($value, 'code');
        $unitValue   = $this->readObjectProperty($value, 'unit');
        $systemValue = $this->readObjectProperty($value, 'system');

        $code   = is_string($codeValue) ? $codeValue : null;
        $unit   = is_string($unitValue) ? $unitValue : $code;
        $system = is_string($systemValue) ? $systemValue : null;

        if ($code === null || $code == '') {
            return [null, true];
        }

        if ($system !== null && $system !== self::UCUM_SYSTEM_URL) {
            return [null, true];
        }

        return [[
            'value'  => (float) $rawValue,
            'code'   => $code,
            'unit'   => $unit ?? $code,
            'system' => $system,
        ], true];
    }

    private function readObjectProperty(object $value, string $property, bool $existsOnly = false): mixed
    {
        if (property_exists($value, $property)) {
            if ($existsOnly) {
                return true;
            }

            return $this->evaluator->normalizeValue($value->$property);
        }

        $getter = 'get' . ucfirst($property);
        if (method_exists($value, $getter)) {
            if ($existsOnly) {
                return true;
            }

            return $this->evaluator->normalizeValue($value->$getter());
        }

        return $existsOnly ? false : null;
    }

    /**
     * @param array{value: float, code: string, unit: string, system: string|null} $left
     * @param array{value: float, code: string, unit: string, system: string|null} $right
     */
    private function compareQuantityValues(array $left, array $right): ?float
    {
        $leftUnit  = $left['code'];
        $rightUnit = $right['code'];

        // Normalize calendar duration keywords to UCUM codes
        $leftUnit  = self::CALENDAR_TO_UCUM[$leftUnit]  ?? $leftUnit;
        $rightUnit = self::CALENDAR_TO_UCUM[$rightUnit] ?? $rightUnit;

        if ($leftUnit === $rightUnit) {
            return $left['value'] - $right['value'];
        }

        if ($this->hasIncomparableDurationMix($leftUnit, $rightUnit)) {
            return null;
        }

        $leftYearMonth  = self::YEAR_MONTH_CONVERSION[$leftUnit]  ?? null;
        $rightYearMonth = self::YEAR_MONTH_CONVERSION[$rightUnit] ?? null;
        if ($leftYearMonth !== null && $rightYearMonth !== null) {
            return $left['value'] * $leftYearMonth - $right['value'] * $rightYearMonth;
        }

        $leftSeconds  = self::CALENDAR_DURATION_SECONDS[$leftUnit]  ?? null;
        $rightSeconds = self::CALENDAR_DURATION_SECONDS[$rightUnit] ?? null;
        if ($leftSeconds !== null && $rightSeconds !== null) {
            return $left['value'] * $leftSeconds - $right['value'] * $rightSeconds;
        }

        $leftConverted  = $this->convertUcumToBase($leftUnit, $left['value']);
        $rightConverted = $this->convertUcumToBase($rightUnit, $right['value']);

        if ($leftConverted === null || $rightConverted === null) {
            return null;
        }

        if ($leftConverted['base'] !== $rightConverted['base']) {
            return null;
        }

        return $leftConverted['value'] - $rightConverted['value'];
    }

    /**
     * @return array{base: string, value: float}|null
     */
    private function convertUcumToBase(string $unit, float $value): ?array
    {
        $definition = self::UCUM_CONVERSIONS[$unit] ?? null;
        if ($definition === null) {
            return null;
        }

        return [
            'base'  => $definition['base'],
            'value' => $value * $definition['factor'],
        ];
    }

    /**
     * Truncate a normalized date/time string to the given precision level.
     *
     * Strips the timezone suffix then takes the leading characters that correspond
     * to the requested precision. Used for ordering comparisons between a date-only
     * value and a datetime value (one has time components, the other does not).
     *
     * Precision levels: 1=year, 2=month, 3=day, 4=hour, 5=minute, 6=second
     */
    private function truncateToMinPrecision(string $value, int $precision): string
    {
        // Strip timezone suffix
        $stripped = (string) preg_replace('/([+-]\d{2}:\d{2}|Z)$/', '', $value);

        // Time-only strings (T-prefixed) use shorter character positions
        if (str_starts_with($stripped, 'T')) {
            return match (true) {
                $precision >= 6 => substr($stripped, 0, 9), // "THH:MM:SS"
                $precision >= 5 => substr($stripped, 0, 6), // "THH:MM"
                default         => substr($stripped, 0, 3), // "THH"
            };
        }

        return match (true) {
            $precision >= 6 => substr($stripped, 0, 19), // "YYYY-MM-DDTHH:MM:SS"
            $precision >= 5 => substr($stripped, 0, 16), // "YYYY-MM-DDTHH:MM"
            $precision >= 4 => substr($stripped, 0, 13), // "YYYY-MM-DDTHH"
            $precision >= 3 => substr($stripped, 0, 10), // "YYYY-MM-DD"
            $precision >= 2 => substr($stripped, 0, 7),  // "YYYY-MM"
            default         => substr($stripped, 0, 4),  // "YYYY"
        };
    }

    private function hasIncomparableDurationMix(string $leftUnit, string $rightUnit): bool
    {
        $leftIsCalendar  = array_key_exists($leftUnit, self::CALENDAR_DURATION_SECONDS);
        $rightIsCalendar = array_key_exists($rightUnit, self::CALENDAR_DURATION_SECONDS);

        if ($leftIsCalendar === $rightIsCalendar) {
            return false;
        }

        return array_key_exists($leftUnit, self::DURATION_ABOVE_WEEK)
            || array_key_exists($rightUnit, self::DURATION_ABOVE_WEEK);
    }
}
