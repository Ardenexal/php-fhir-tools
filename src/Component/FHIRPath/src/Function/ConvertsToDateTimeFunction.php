<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * convertsToDateTime(): Boolean
 *
 * Returns true if the input value can be converted to a DateTime without error,
 * false if the conversion would fail. Returns empty {} if the input is empty.
 *
 * Uses the same conversion rules as toDateTime().
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ConvertsToDateTimeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('convertsToDateTime');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        return Collection::single(ToDateTimeFunction::tryConvert($input->first()) !== null);
    }
}
