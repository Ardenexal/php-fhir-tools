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
 * Per FHIRPath spec:
 * - Empty search string: replacement is inserted at every position (before each char and at end)
 * - Empty collection args propagate empty
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

        $searchCollection = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($searchCollection->isEmpty()) {
            return Collection::empty();
        }

        $search = $searchCollection->first();
        if (!is_string($search)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'search', 'string');
        }

        $replaceCollection = $evaluator->evaluateWithContext($parameters[1], $context);
        if ($replaceCollection->isEmpty()) {
            return Collection::empty();
        }

        $replace = $replaceCollection->first();
        if (!is_string($replace)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'replacement', 'string');
        }

        // Per FHIRPath spec: empty search string inserts replacement at every position
        // e.g. 'abc'.replace('', 'x') = 'xaxbxcx'
        if ($search === '') {
            if ($string === '') {
                return Collection::single($replace);
            }

            $chars  = str_split($string, 1);
            $result = $replace . implode($replace, $chars) . $replace;

            return Collection::single($result);
        }

        return Collection::single(str_replace($search, $replace, $string));
    }
}
