<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\UnaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;

/**
 * sort() function - Sorts a collection by natural order or by projection expressions.
 *
 * Usage:
 * - collection.sort() - sorts by natural order (ascending)
 * - collection.sort($this) - sorts by expression (ascending)
 * - collection.sort(-$this) - sorts by expression (descending, using unary minus)
 * - collection.sort(key1, key2) - multi-key sort with tie-breaking
 *
 * @author FHIR Tools Contributors
 */
final class SortFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('sort');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Optimization: empty or single-item collections need no sorting
        if ($input->isEmpty() || $input->count() === 1) {
            return $input;
        }

        // Natural sort (no parameters)
        if (count($parameters) === 0) {
            return $this->naturalSort($input);
        }

        // Expression-based sort (1+ parameters)
        return $this->expressionSort($input, $parameters, $context);
    }

    /**
     * Perform natural ascending sort.
     */
    private function naturalSort(Collection $input): Collection
    {
        $items = $input->toArray();

        // Validate all items are comparable
        $firstType = $this->getComparableType($items[0]);
        foreach ($items as $item) {
            if ($this->getComparableType($item) !== $firstType) {
                throw new EvaluationException(sprintf('Cannot sort collection with mixed types: %s and %s', $firstType ?? 'unknown', $this->getComparableType($item) ?? 'unknown'));
            }
        }

        // Sort using compareValues for consistent type handling
        usort($items, fn ($a, $b) => $this->compareValues($a, $b));

        return Collection::from($items);
    }

    /**
     * Perform expression-based sort with one or more sort keys.
     *
     * @param array<ExpressionNode> $parameters
     */
    private function expressionSort(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        // Parse sort keys: extract expression and descending flag
        $sortKeys = [];
        foreach ($parameters as $param) {
            if ($param instanceof UnaryOperatorNode && $param->getOperator() === TokenType::MINUS) {
                // Descending sort: -expression
                $sortKeys[] = [
                    'expr' => $param->getOperand(),
                    'desc' => true,
                ];
            } else {
                // Ascending sort: expression
                $sortKeys[] = [
                    'expr' => $param,
                    'desc' => false,
                ];
            }
        }

        // Build sort key values for each item
        $items     = $input->toArray();
        $keyValues = [];

        foreach ($items as $index => $item) {
            $itemContext = $context
                ->withCurrentNode($item)
                ->withIterationVariables($item, $index, $input->count());

            $keyValues[$index] = [];

            foreach ($sortKeys as $keyIndex => $sortKey) {
                /** @var ExpressionNode $expr */
                $expr   = $sortKey['expr'];
                $result = $evaluator->evaluateWithContext($expr, $itemContext);

                // Handle empty result
                if ($result->isEmpty()) {
                    $keyValues[$index][$keyIndex] = null;
                    continue;
                }

                // Handle single value
                if ($result->isSingle()) {
                    $keyValues[$index][$keyIndex] = $result->first();
                    continue;
                }

                // Multiple values are ambiguous for sorting
                throw new EvaluationException(sprintf('Sort expression at position %d returned multiple values (%d), expected single value or empty', $keyIndex, $result->count()));
            }
        }

        // Create multi-key comparator
        $comparator = function(int $aIndex, int $bIndex) use ($keyValues, $sortKeys): int {
            foreach ($sortKeys as $keyIndex => $sortKey) {
                $aValue = $keyValues[$aIndex][$keyIndex];
                $bValue = $keyValues[$bIndex][$keyIndex];

                // Compare values
                $cmp = $this->compareValues($aValue, $bValue);

                // Apply descending flag
                if ($sortKey['desc']) {
                    $cmp = -$cmp;
                }

                // Return on first non-equal comparison
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            return 0; // All keys equal
        };

        // Sort items by indices
        $indices = array_keys($items);
        usort($indices, $comparator);

        // Build sorted array
        $sorted = [];
        foreach ($indices as $index) {
            $sorted[] = $items[$index];
        }

        return Collection::from($sorted);
    }

    /**
     * Compare two values for sorting.
     *
     * @return int Negative if $a < $b, positive if $a > $b, zero if equal
     */
    private function compareValues(mixed $a, mixed $b): int
    {
        // Per FHIRPath spec: items with empty sort keys are placed at the END of the sorted sequence.
        // This means null sorts LAST in ascending order (NULLS LAST).
        if ($a === null && $b === null) {
            return 0;
        }
        if ($a === null) {
            return 1;   // null > non-null → null sorts after (at end)
        }
        if ($b === null) {
            return -1;  // non-null < null → non-null sorts before
        }

        // Normalize non-decimal Stringable objects (e.g. FHIR primitive wrappers) to strings
        if ($a instanceof \Stringable && !($a instanceof FHIRPathDecimal)) {
            $a = (string) $a;
        }
        if ($b instanceof \Stringable && !($b instanceof FHIRPathDecimal)) {
            $b = (string) $b;
        }

        // Type validation
        $aType = $this->getComparableType($a);
        $bType = $this->getComparableType($b);

        if ($aType !== $bType) {
            throw new EvaluationException(sprintf('Cannot compare incompatible types for sorting: %s and %s', $aType ?? 'unknown', $bType ?? 'unknown'));
        }

        // FHIRPathDecimal uses bccomp for arbitrary-precision numeric comparison
        if ($a instanceof FHIRPathDecimal || $b instanceof FHIRPathDecimal) {
            $aStr  = $a instanceof FHIRPathDecimal ? $a->value : (string) $a;
            $bStr  = $b instanceof FHIRPathDecimal ? $b->value : (string) $b;
            $scale = max(
                $a instanceof FHIRPathDecimal ? $a->precision : 0,
                $b instanceof FHIRPathDecimal ? $b->precision : 0,
            ) + 4;

            // Assert numeric-string so bccomp receives the correct type per PHPStan
            assert(is_numeric($aStr));
            assert(is_numeric($bStr));

            return bccomp($aStr, $bStr, $scale);
        }

        return $a <=> $b;
    }

    /**
     * Get the comparable type category for a value.
     */
    private function getComparableType(mixed $value): ?string
    {
        if ($value instanceof FHIRPathDecimal || is_int($value) || is_float($value)) {
            return 'numeric';
        }
        // FHIRPathDecimal is already handled above; the instanceof check here is always false
        // but we keep \Stringable for other wrapper objects (e.g. FHIR primitive wrappers).
        if (is_string($value) || $value instanceof \Stringable) {
            return 'string';
        }
        if (is_bool($value)) {
            return 'boolean';
        }

        return null;
    }
}
