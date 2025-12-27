<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Interface for FHIRPath functions.
 *
 * All FHIRPath functions must implement this interface to be registered
 * and executed by the evaluator.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
interface FunctionInterface
{
    /**
     * Get the function name.
     *
     * @return string The function name (e.g., 'where', 'select', 'first')
     */
    public function getName(): string;

    /**
     * Execute the function with given input collection and parameters.
     *
     * @param Collection        $input      The input collection to operate on
     * @param array<int, mixed> $parameters Function parameters (expressions or values)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection The result collection
     *
     * @throws EvaluationException
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection;
}
