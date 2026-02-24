<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toQuantity([unit : String]) : Quantity
 *
 * Converts the input value to a Quantity. Returns empty if the value cannot be
 * meaningfully converted, or if the unit parameter is given but does not match.
 *
 * Conversion rules per the FHIRPath spec §5.5:
 *  - Integer or Decimal       → Quantity with the numeric value and unit '1'
 *  - Boolean true/false       → 1.0 / 0.0 with unit '1'
 *  - String '5'               → {value: 5.0, unit: '1'} (plain numeric string)
 *  - String "5 'mg'"          → {value: 5.0, unit: 'mg'} (FHIRPath quantity literal)
 *  - Quantity array           → pass-through (normalised to float value)
 *  - If unit parameter given and the resulting unit does not match → empty {}
 *  - Anything else            → empty {}
 *  - Empty input              → empty {}
 *
 * Internal Quantity representation: ['value' => float, 'unit' => string]
 *
 * The static tryConvert() helper is shared with convertsToQuantity().
 *
 * @author FHIR Tools Contributors
 */
final class ToQuantityFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toQuantity');
    }

    /**
     * Execute the toQuantity conversion on the input collection.
     *
     * Converts the first item in the input to a Quantity representation, optionally
     * filtering by a required unit.
     *
     * @param Collection        $input      The input collection (uses first item)
     * @param array<int, mixed> $parameters [0] = optional unit filter expression
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the Quantity, or empty if conversion fails
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no unit filter) or 1 parameter (unit filter string)
        $this->validateParameterCount($parameters, 0, 1);
        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $requiredUnit = $this->resolveUnitParameter($parameters, $context);

        $result = self::tryConvert($input->first(), $requiredUnit);

        return $result !== null ? Collection::single($result) : Collection::empty();
    }

    /**
     * Attempt to convert a value to a Quantity array ['value' => float, 'unit' => string].
     *
     * Returns null when the value cannot be converted (which becomes empty {} in FHIRPath).
     * Used by convertsToQuantity() to check convertibility without throwing.
     *
     * @return array{value: float, unit: string}|null
     */
    public static function tryConvert(mixed $value, ?string $requiredUnit = null): ?array
    {
        $quantity = null;

        if (is_int($value) || is_float($value)) {
            $quantity = ['value' => (float) $value, 'unit' => '1'];
        } elseif (is_bool($value)) {
            $quantity = ['value' => $value ? 1.0 : 0.0, 'unit' => '1'];
        } elseif (is_string($value)) {
            $quantity = self::parseQuantityString($value);
        } elseif (is_array($value) && array_key_exists('value', $value) && array_key_exists('unit', $value) && is_numeric($value['value']) && is_string($value['unit'])) {
            $quantity = ['value' => (float) $value['value'], 'unit' => $value['unit']];
        }

        if ($quantity === null) {
            return null;
        }

        if ($requiredUnit !== null && $quantity['unit'] !== $requiredUnit) {
            return null;
        }

        return $quantity;
    }

    /**
     * Resolve the optional unit parameter from the parameter list.
     *
     * @param array<int, mixed> $parameters
     */
    private function resolveUnitParameter(array $parameters, EvaluationContext $context): ?string
    {
        if (count($parameters) !== 1) {
            return null;
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            return null;
        }

        $unitResult = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($unitResult->isEmpty()) {
            return null;
        }

        $unitValue = $unitResult->first();

        return is_string($unitValue) ? $unitValue : null;
    }

    /**
     * Parse a FHIRPath quantity string.
     *
     * Accepted formats:
     *  - "10 'mg'"   → {value: 10.0, unit: 'mg'}   (FHIRPath literal with quoted unit)
     *  - "10'mg'"    → {value: 10.0, unit: 'mg'}   (no space before quote)
     *  - "10"        → {value: 10.0, unit: '1'}    (plain numeric, dimensionless)
     *  - "-5.5 'kg'" → {value: -5.5, unit: 'kg'}
     *
     * @param string $value The string to parse
     *
     * @return array{value: float, unit: string}|null The parsed Quantity or null if invalid
     */
    private static function parseQuantityString(string $value): ?array
    {
        $trimmed = trim($value);

        // FHIRPath quantity literal: number followed by a quoted unit (e.g. "10 'mg'")
        // Pattern: optional +/-, digits, optional decimal part, optional space, quoted unit
        // preg_match returns 1 on successful match, 0 on no match
        if (preg_match("/^([+-]?\d+(?:\.\d+)?)\s*'([^']*)'\$/", $trimmed, $matches) === 1) {
            // $matches[1] = the numeric value, $matches[2] = the unit (inside quotes)
            return ['value' => (float) $matches[1], 'unit' => $matches[2]];
        }

        // Plain numeric string → dimensionless Quantity with unit '1'
        // Pattern: optional +/-, digits, optional decimal part, end of string
        // preg_match returns 1 on successful match
        if (preg_match('/^[+-]?\d+(?:\.\d+)?$/', $trimmed) === 1) {
            // '1' represents a dimensionless quantity (no specific unit)
            return ['value' => (float) $trimmed, 'unit' => '1'];
        }

        return null;
    }
}
