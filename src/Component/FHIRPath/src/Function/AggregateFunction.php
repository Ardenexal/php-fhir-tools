<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * aggregate(aggregator: expression [, init: value]): value
 *
 * Reduces a collection to a single value by iteratively applying the
 * aggregator expression. The aggregator has access to:
 *   - `$this`  — the current item
 *   - `$index` — the 0-based position in the collection
 *   - `$total` — the accumulated result so far
 *
 * The `init` argument (optional) is evaluated once to provide the initial
 * value of `$total`. If omitted, `$total` starts as empty (null).
 *
 * Example: item.aggregate($this + $total, 0)  // sum
 *
 * Spec reference: FHIRPath §7 (STU)
 *
 * @author FHIR Tools Contributors
 */
final class AggregateFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('aggregate');
    }

    /**
     * Execute the aggregate function on the input collection.
     *
     * Iterates through each item in the input collection, applying the aggregator
     * expression with access to $this (current item), $index (position), and
     * $total (accumulated result). The final accumulated value is returned.
     *
     * @param Collection        $input      The input collection to aggregate
     * @param array<int, mixed> $parameters [0] = aggregator expression, [1] = optional init value expression
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the final aggregated value, or empty if result is null
     *
     * @throws EvaluationException If evaluator is not set in context or parameter count is invalid
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 1 parameter (aggregator) or 2 parameters (aggregator + init)
        $this->validateParameterCount($parameters, 1, 2);

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            // Error codes: 0 = line number (unknown), 0 = column number (unknown)
            throw new EvaluationException('Evaluator not set in context', 0, 0);
        }

        /** @var ExpressionNode $aggregator */
        $aggregator = $parameters[0];

        // Evaluate init once before iteration to get starting $total
        $accumulator = null;
        // Check if we have 2 parameters (aggregator + init value)
        if (count($parameters) === 2 && $parameters[1] instanceof ExpressionNode) {
            $initResult  = $evaluator->evaluateWithContext($parameters[1], $context);
            $accumulator = $initResult->isEmpty() ? null : $initResult->first();
        }

        foreach ($input as $index => $item) {
            // Each iteration gets $this, $index, and $total in its context
            $iterContext = $context
                ->withCurrentNode($item)
                ->withVariable('this', $item)
                ->withVariable('index', $index)
                ->withVariable('total', $accumulator);

            $result      = $evaluator->evaluateWithContext($aggregator, $iterContext);
            $accumulator = $result->isEmpty() ? null : $result->first();
        }

        return $accumulator !== null ? Collection::single($accumulator) : Collection::empty();
    }
}
