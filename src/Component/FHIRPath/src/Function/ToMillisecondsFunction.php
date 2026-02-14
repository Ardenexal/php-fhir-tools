<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use DateTime;

/**
 * FHIRPath toMilliseconds() function.
 *
 * Converts a DateTime to milliseconds since Unix epoch (1970-01-01 00:00:00 UTC).
 * For single DateTime item: returns milliseconds as integer
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class ToMillisecondsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toMilliseconds');
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
                throw EvaluationException::invalidFunctionParameter('toMilliseconds', 'DateTime', gettype($item));
            }

            // Get milliseconds since epoch
            $items[] = (int) ($item->getTimestamp() * 1000);
        }

        return Collection::from($items);
    }
}
