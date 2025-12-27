<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * count() function - Returns the number of items in the collection
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class CountFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('count');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        return Collection::single($input->count());
    }
}
