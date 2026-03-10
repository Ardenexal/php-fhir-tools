<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDate;

/**
 * FHIRPath today() function.
 *
 * Returns the current date (date only, no time) as a FHIRPathDate wrapper.
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

        $now = new \DateTimeImmutable();

        return Collection::single(new FHIRPathDate($now->format('Y-m-d')));
    }
}
