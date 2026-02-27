<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * substring(start, [length]) function - Extracts a substring
 *
 * Per FHIRPath spec:
 * - start is 0-based
 * - Negative start returns empty
 * - start >= length of string returns empty
 * - length is optional; if omitted, returns to end of string
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class SubstringFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('substring');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1, 2);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $str = $input->first();
        if (!is_string($str)) {
            throw EvaluationException::invalidFunctionParameter('substring', 'input', 'string');
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $startCollection = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($startCollection->isEmpty()) {
            return Collection::empty();
        }

        $start = $startCollection->first();
        if (!is_int($start)) {
            throw EvaluationException::invalidFunctionParameter('substring', 'start', 'integer');
        }

        // Per spec: negative start returns empty
        if ($start < 0) {
            return Collection::empty();
        }

        // Per spec: start >= string length returns empty
        if ($start >= strlen($str)) {
            return Collection::empty();
        }

        // Optional length parameter
        $length = null;
        if (count($parameters) === 2) {
            $lengthCollection = $evaluator->evaluateWithContext($parameters[1], $context);
            if ($lengthCollection->isEmpty()) {
                return Collection::empty();
            }

            $length = $lengthCollection->first();
            if (!is_int($length) || $length < 0) {
                throw EvaluationException::invalidFunctionParameter('substring', 'length', 'non-negative integer');
            }
        }

        $result = $length !== null ? substr($str, $start, $length) : substr($str, $start);

        // substr returns '' when length is 0 or start is at the boundary — that is valid per spec
        return Collection::single($result);
    }
}
