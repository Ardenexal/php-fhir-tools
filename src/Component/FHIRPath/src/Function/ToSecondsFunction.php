<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use DateTime;

/**
 * FHIRPath toSeconds() function.
 *
 * Converts a DateTime to seconds since Unix epoch (1970-01-01 00:00:00 UTC).
 * For single DateTime item: returns seconds as integer
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class ToSecondsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toSeconds');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        foreach ($input as $item) {
            if (!$item instanceof \DateTime) {
                throw EvaluationException::invalidFunctionParameter('toSeconds', 'DateTime', gettype($item));
            }

            $items[] = $item->getTimestamp();
        }

        return Collection::from($items);
    }
}
