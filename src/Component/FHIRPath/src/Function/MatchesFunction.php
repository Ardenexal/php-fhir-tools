<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath matches() function.
 *
 * Returns true if the input string matches the given regular expression pattern.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class MatchesFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('matches');
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

        $pattern = $evaluator->evaluate($parameters[0], $context)->first();
        if (!is_string($pattern)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'pattern', 'string');
        }

        // Add delimiters if not present
        if (!str_starts_with($pattern, '/')) {
            $pattern = '/' . $pattern . '/';
        }

        $result = @preg_match($pattern, $string);

        if ($result === false) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'pattern', 'valid regular expression');
        }

        return Collection::single($result === 1);
    }
}
