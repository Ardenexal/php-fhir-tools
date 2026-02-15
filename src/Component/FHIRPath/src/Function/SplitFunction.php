<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath split() function.
 *
 * Splits the input string into a collection of strings using the given delimiter.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class SplitFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('split');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $input->first();
        if (!is_string($string)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'input', 'string');
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $delimiter = $evaluator->evaluate($parameters[0], $context)->first();
        if (!is_string($delimiter)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'delimiter', 'string');
        }

        if ($delimiter === '') {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'delimiter', 'non-empty string');
        }

        $parts = explode($delimiter, $string);

        return Collection::from($parts);
    }
}
