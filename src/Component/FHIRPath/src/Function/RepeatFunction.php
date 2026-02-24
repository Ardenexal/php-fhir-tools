<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * repeat(projection: expression) : collection
 *
 * Applies the projection expression to each item in the collection and unions
 * the results with the current collection. This process repeats on the newly
 * added items until no more new items appear (fixed-point / transitive closure).
 *
 * Cycle-safe: items are tracked by identity key so the same object is never
 * visited twice, preventing infinite loops in circular structures.
 *
 * Spec reference: FHIRPath §5.3.13 (N1 draft) / §5.8 (some editions).
 *
 * @author FHIR Tools Contributors
 */
final class RepeatFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('repeat');
    }

    /**
     * Execute the repeat function on the input collection.
     *
     * Applies the projection expression recursively to discover all reachable items
     * through transitive closure. Tracks visited items by identity to prevent infinite loops.
     *
     * @param Collection        $input      The input collection to start from
     * @param array<int, mixed> $parameters [0] = projection expression to apply recursively
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection All items reachable through repeated application of the projection
     *
     * @throws EvaluationException If evaluator is not available or projection is not an expression
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects exactly 1 parameter (the projection expression)
        $this->validateParameterCount($parameters, 1);
        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $projection = $parameters[0];
        if (!$projection instanceof ExpressionNode) {
            throw new EvaluationException('repeat() requires an expression argument', 0, 0);
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context', 0, 0);
        }

        // Seed result with the input items
        $result = [];
        $seen   = [];

        foreach ($input as $item) {
            $key = $this->itemKey($item);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $result[]   = $item;
            }
        }

        // Iteratively apply the projection to newly added items only.
        // $frontier holds items that have not yet had the projection applied.
        $frontier = $result;

        while (!empty($frontier)) {
            $nextFrontier = [];

            foreach ($frontier as $item) {
                $itemContext = $context->withCurrentNode($item);
                $projected   = $evaluator->evaluateWithContext($projection, $itemContext);

                foreach ($projected as $projectedItem) {
                    $key = $this->itemKey($projectedItem);

                    if (!isset($seen[$key])) {
                        $seen[$key]     = true;
                        $result[]       = $projectedItem;
                        $nextFrontier[] = $projectedItem;
                    }
                }
            }

            $frontier = $nextFrontier;
        }

        return Collection::from($result);
    }

    /**
     * Generate a unique key for an item to track whether we've seen it before.
     *
     * This prevents infinite loops by ensuring each unique item is processed only once.
     *
     * @param mixed $item The item to generate a key for
     *
     * @return string A unique string identifier for this item
     */
    private function itemKey(mixed $item): string
    {
        // For simple values (strings, numbers, booleans, null), serialize them
        if (is_scalar($item) || $item === null) {
            return serialize($item);
        }

        // For objects, use PHP's built-in object hash (unique per object instance)
        if (is_object($item)) {
            return spl_object_hash($item);
        }

        // For arrays and other types, serialize them
        return serialize($item);
    }
}
