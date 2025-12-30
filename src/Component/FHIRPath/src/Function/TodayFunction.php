<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath today() function.
 *
 * Returns the current date (date only, no time).
 * Takes no parameters.
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class TodayFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('today');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        // Return date portion as string in format YYYY-MM-DD
        $now = new \DateTime();

        return Collection::single($now->format('Y-m-d'));
    }
}
