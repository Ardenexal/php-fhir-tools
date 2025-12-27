<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * substring(start, [length]) function - Extracts a substring
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

        $startCollection = $parameters[0];
        if (!($startCollection instanceof Collection) || $startCollection->isEmpty()) {
            throw EvaluationException::invalidFunctionParameter('substring', 'start', 'non-empty integer collection');
        }

        $start = $startCollection->first();
        if (!is_int($start) || $start < 0) {
            throw EvaluationException::invalidFunctionParameter('substring', 'start', 'non-negative integer');
        }

        // Optional length parameter
        $length = null;
        if (count($parameters) === 2) {
            $lengthCollection = $parameters[1];
            if (!($lengthCollection instanceof Collection) || $lengthCollection->isEmpty()) {
                throw EvaluationException::invalidFunctionParameter('substring', 'length', 'non-empty integer collection');
            }

            $length = $lengthCollection->first();
            if (!is_int($length) || $length < 0) {
                throw EvaluationException::invalidFunctionParameter('substring', 'length', 'non-negative integer');
            }
        }

        $result = $length !== null ? substr($str, $start, $length) : substr($str, $start);

        return Collection::single($result);
    }
}
