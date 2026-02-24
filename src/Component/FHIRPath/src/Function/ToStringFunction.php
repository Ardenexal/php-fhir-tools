<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toString(): String
 *
 * Converts the input value to a String. Returns empty if the value cannot be
 * meaningfully converted to a string.
 *
 * Conversion rules per the FHIRPath spec §5.5:
 *  - String                              → pass-through
 *  - Boolean true/false                  → 'true' / 'false'
 *  - Integer                             → decimal string (e.g. '42')
 *  - Decimal (float)                     → decimal string with at least one digit
 *                                          after the point (e.g. '3.14', '42.0')
 *  - Quantity ['value','unit']           → "{value} '{unit}'" (FHIRPath literal)
 *  - Date/DateTime/Time strings          → the string as-is (already strings)
 *  - Complex types / non-scalar arrays  → empty {}
 *  - Empty input                         → empty {}
 *
 * The static tryConvert() helper is shared with convertsToString().
 *
 * @author FHIR Tools Contributors
 */
final class ToStringFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toString');
    }

    /**
     * Execute the toString conversion on the input collection.
     *
     * Converts the first item in the input to its string representation according
     * to FHIRPath conversion rules.
     *
     * @param Collection        $input      The input collection (uses first item)
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the string, or empty if conversion fails
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for toString)
        $this->validateParameterCount($parameters, 0);
        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $result = self::tryConvert($input->first());

        return $result !== null ? Collection::single($result) : Collection::empty();
    }

    /**
     * Attempt to convert a value to a string.
     *
     * Returns null when the value cannot be converted (which becomes empty {} in FHIRPath).
     * Used by convertsToString() to check convertibility without throwing.
     */
    public static function tryConvert(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            if (is_nan($value) || is_infinite($value)) {
                return null;
            }

            $str = (string) $value;

            // Ensure at least one digit after the decimal point (spec requirement)
            if (!str_contains($str, '.')) {
                $str .= '.0';
            }

            return $str;
        }

        // Quantity array representation: ['value' => numeric, 'unit' => string]
        if (is_array($value) && array_key_exists('value', $value) && array_key_exists('unit', $value) && is_numeric($value['value']) && is_string($value['unit'])) {
            $floatVal = (float) $value['value'];
            $strVal   = self::formatDecimal($floatVal);

            return "{$strVal} '{$value['unit']}'";
        }

        // Other complex types (non-Quantity arrays, objects) → empty
        return null;
    }

    /**
     * Format a float as a decimal string, ensuring at least one digit after the decimal.
     *
     * FHIRPath requires decimal values to always have at least one digit after the
     * decimal point (e.g., '42.0' instead of '42').
     *
     * @param float $value The float value to format
     *
     * @return string The formatted decimal string
     */
    private static function formatDecimal(float $value): string
    {
        $str = (string) $value;

        // Add '.0' if there's no decimal point (FHIRPath spec requirement)
        if (!str_contains($str, '.')) {
            $str .= '.0';  // '.0' is the minimum decimal representation
        }

        return $str;
    }
}
