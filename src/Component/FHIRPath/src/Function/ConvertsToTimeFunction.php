<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * convertsToTime(): Boolean
 *
 * Returns true if the input value can be converted to a Time without error,
 * false if the conversion would fail. Returns empty {} if the input is empty.
 *
 * Uses the same conversion rules as toTime().
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ConvertsToTimeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('convertsToTime');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        return Collection::single(ToTimeFunction::tryConvert($input->first()) !== null);
    }
}
