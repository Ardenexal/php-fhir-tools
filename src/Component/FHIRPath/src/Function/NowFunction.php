<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use DateTime;

/**
 * FHIRPath now() function.
 *
 * Returns the current date and time as a DateTime.
 * Takes no parameters.
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class NowFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('now');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        return Collection::single(new \DateTime());
    }
}
