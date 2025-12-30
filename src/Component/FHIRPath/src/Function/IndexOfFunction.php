<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath indexOf() function.
 *
 * Returns the zero-based index of the first occurrence of the substring in the input string,
 * or -1 if the substring is not found.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class IndexOfFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('indexOf');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $input->first();
        if (!is_string($string)) {
            throw EvaluationException::invalidFunctionParameter(
                $this->getName(),
                'Input must be a string'
            );
        }

        $substring = $context->getEvaluator()->evaluate($parameters[0], $context)->first();
        if (!is_string($substring)) {
            throw EvaluationException::invalidFunctionParameter(
                $this->getName(),
                'Substring parameter must be a string'
            );
        }

        $position = strpos($string, $substring);
        
        return Collection::single($position !== false ? $position : -1);
    }
}
