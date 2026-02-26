<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTime;

/**
 * FHIRPath timeOfDay() function.
 *
 * Returns the current time of day (time only, no date) as a FHIRPathTime wrapper.
 * Takes no parameters.
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class TimeOfDayFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('timeOfDay');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        $now = new \DateTimeImmutable();

        return Collection::single(new FHIRPathTime($now->format('H:i:s')));
    }
}
