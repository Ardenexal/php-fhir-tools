<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * length() function - Returns the length of a string
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class LengthFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('length');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $str = $input->first();
        if (!is_string($str)) {
            throw EvaluationException::invalidFunctionParameter('length', 'input', 'string');
        }

        return Collection::single(strlen($str));
    }
}
