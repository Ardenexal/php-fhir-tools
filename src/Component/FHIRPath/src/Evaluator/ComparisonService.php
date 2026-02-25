<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

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
    public function __construct(
        private readonly FHIRPathEvaluator $evaluator
    ) {
    }

    /**
     * Evaluate equality/equivalence comparison operators.
     *
     * Operators: =, !=, ~, !~
     *
     * FHIRPath semantics:
     * - Empty collections return empty result
     * - Single-item collections compared by value
     * - Multi-item collections compared as sets (order-independent)
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

        // Check for DateTime precision compatibility
        if ($this->isDateTimeString($leftValue) && $this->isDateTimeString($rightValue)) {
            $leftPrecision  = $this->getDateTimePrecision($leftValue);
            $rightPrecision = $this->getDateTimePrecision($rightValue);

            // Different precisions are incomparable
            if ($leftPrecision !== $rightPrecision) {
                return Collection::empty();
            }

            // Normalize DateTime strings for comparison
            $leftValue  = $this->normalizeDateTimeString($leftValue);
            $rightValue = $this->normalizeDateTimeString($rightValue);
        }

        $result = $operation($leftValue, $rightValue);

        return Collection::single($result);
    }

    /**
     * Compare two collections for equality using set semantics (order-independent).
     *
     * @param array<mixed> $left
     * @param array<mixed> $right
     *
     * @return bool|null True if equal, false if not equal, null if incomparable
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

        // Track which right items have been matched to prevent double-matching
        $matchedIndices = [];

        // For each left item, find a matching right item
        foreach ($left as $leftItem) {
            $foundMatch = false;

            foreach ($right as $rightIndex => $rightItem) {
                // Skip already matched items
                if (in_array($rightIndex, $matchedIndices, true)) {
                    continue;
                }

                $isEqual = $this->valuesEqual($leftItem, $rightItem, $useEquivalence);

                // If incomparable (null), the entire comparison is incomparable
                if ($isEqual === null) {
                    return null;
                }

                if ($isEqual === true) {
                    $matchedIndices[] = $rightIndex;
                    $foundMatch       = true;
                    break;
                }
            }

            // If any left item has no match, collections are not equal
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

        // DateTime precision-aware comparison
        if ($this->isDateTimeString($a) && $this->isDateTimeString($b)) {
            $aPrecision = $this->getDateTimePrecision($a);
            $bPrecision = $this->getDateTimePrecision($b);

            // Different precisions are incomparable
            if ($aPrecision !== $bPrecision) {
                return null;
            }

            // Same precision: normalize and compare string values
            $aValue = $this->normalizeDateTimeString($a);
            $bValue = $this->normalizeDateTimeString($b);

            return $aValue === $bValue;
        }

        // For equivalence mode: apply type normalization
        if ($useEquivalence) {
            // Numeric equivalence: 1 ~ 1.0 should be true
            if (is_numeric($a) && is_numeric($b)) {
                // Use loose comparison for numeric values
                return $a == $b;
            }

            // For other types, use loose comparison
            return $a == $b;
        }

        // For equality mode: strict type-preserving comparison
        return $a === $b;
    }

    /**
     * Detect if a value is a DateTime string literal.
     *
     * DateTime literals start with @ followed by YYYY or T (for time-only).
     */
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

    private function isDateTimeString(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Check for @ prefix followed by year (YYYY) or time (T)
        return preg_match('/^@(\d{4}|T)/', $value) === 1;
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
    private function getDateTimePrecision(string $value): ?int
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
}
