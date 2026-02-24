<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath matchesFull() function.
 *
 * Returns true only if the entire input string matches the given regular
 * expression (anchored full-string match). Equivalent to matches() but the
 * pattern is automatically wrapped in '^(?:...)$'.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class MatchesFullFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('matchesFull');
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

        // Empty pattern parameter collection → propagate empty per FHIRPath spec
        $patternResult = $evaluator->evaluate($parameters[0], $context);
        if ($patternResult->isEmpty()) {
            return Collection::empty();
        }

        $pattern = $patternResult->first();
        if (!is_string($pattern)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'pattern', 'string');
        }

        // Strip any existing delimiters before anchoring, then rebuild with DOTALL.
        if (str_starts_with($pattern, '/')) {
            $lastSlash = strrpos($pattern, '/');
            $inner     = $lastSlash !== false ? substr($pattern, 1, $lastSlash - 1) : $pattern;
        } else {
            $inner = $pattern;
        }

        $anchored = '/^(?:' . $inner . ')$/s';

        $result = @preg_match($anchored, $string);

        if ($result === false) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'pattern', 'valid regular expression');
        }

        return Collection::single($result === 1);
    }
}
