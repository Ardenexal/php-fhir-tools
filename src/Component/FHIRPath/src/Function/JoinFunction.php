<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath join() function.
 *
 * Joins a collection of strings with the given separator.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class JoinFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('join');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $separator = $evaluator->evaluate($parameters[0], $context)->first();
        if (!is_string($separator)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'separator', 'string');
        }

        // Validate all collection items are strings
        $strings = [];
        foreach ($input->toArray() as $item) {
            if (!is_string($item)) {
                throw EvaluationException::invalidFunctionParameter($this->getName(), 'collection item', 'string');
            }
            $strings[] = $item;
        }

        return Collection::single(implode($separator, $strings));
    }
}
