<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath toDecimal() function.
 *
 * Converts the input value to a decimal (float). Returns empty if the value
 * cannot be meaningfully converted.
 *
 * Conversion rules per the FHIRPath spec:
 *  - integer / float → (float) cast
 *  - numeric string  → (float) cast
 *  - non-numeric string → empty
 *  - boolean → 1.0 (true) or 0.0 (false)
 *  - empty input → empty
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class ToDecimalFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toDecimal');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $result = self::tryConvert($input->first());

        return $result !== null ? Collection::single($result) : Collection::empty();
    }

    /**
     * Attempt to convert a value to float.
     *
     * Returns null when the value cannot be converted (becomes empty {} in FHIRPath).
     * Used by convertsToDecimal() to check convertibility without throwing.
     */
    public static function tryConvert(mixed $value): ?float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_bool($value)) {
            return $value ? 1.0 : 0.0;
        }

        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        return null;
    }
}
