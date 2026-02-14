<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath replace() function.
 *
 * Returns the input string with all occurrences of the search string replaced
 * with the replacement string.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class ReplaceFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('replace');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 2);

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

        $search = $evaluator->evaluate($parameters[0], $context)->first();
        if (!is_string($search)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'search', 'string');
        }

        $replace = $evaluator->evaluate($parameters[1], $context)->first();
        if (!is_string($replace)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'replacement', 'string');
        }

        return Collection::single(str_replace($search, $replace, $string));
    }
}
