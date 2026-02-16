<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * empty() function - Returns true if the input collection is empty
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class EmptyFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('empty');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        return Collection::single($input->isEmpty());
    }
}
