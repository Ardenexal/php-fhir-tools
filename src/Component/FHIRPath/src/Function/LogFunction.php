<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath log() function.
 *
 * Returns the logarithm of the input value. If no base is given, returns the
 * base-10 logarithm. If a base is given, returns log_base(value).
 *
 * Returns empty for any mathematically undefined case:
 *  - input value <= 0
 *  - base <= 0 or base == 1 (undefined logarithm base)
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class LogFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('log');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        // Resolve optional base parameter
        $base = null;
        if (!empty($parameters)) {
            $evaluator = $context->getEvaluator();
            if ($evaluator === null) {
                throw new EvaluationException('Evaluator not available in context');
            }

            $baseResult = $evaluator->evaluate($parameters[0], $context);
            if (!$baseResult->isEmpty()) {
                $baseValue   = $baseResult->first();
                $baseNumeric = $this->extractNumeric($baseValue);
                if ($baseNumeric === null) {
                    throw EvaluationException::invalidFunctionParameter('log', 'base', 'number');
                }

                $base = (float) $baseNumeric;

                // base <= 0 or base == 1 makes the logarithm undefined
                if ($base <= 0.0 || $base === 1.0) {
                    return Collection::empty();
                }
            }
        }

        $items = [];
        foreach ($input as $item) {
            $numeric = $this->extractNumeric($item);
            if ($numeric === null) {
                throw EvaluationException::invalidFunctionParameter('log', 'numeric value', gettype($item));
            }

            $value = (float) $numeric;
            if ($value <= 0) {
                return Collection::empty();
            }

            $items[] = $base !== null ? log($value, $base) : log10($value);
        }

        return Collection::from($items);
    }
}
