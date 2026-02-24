<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toBoolean(): Boolean
 *
 * Converts the input value to a boolean. Returns empty if the value cannot be
 * meaningfully converted.
 *
 * Conversion rules per the FHIRPath spec §5.5:
 *  - Boolean                               → pass-through
 *  - Integer 1 / 0                         → true / false
 *  - String 'true','1','yes','y','on'      → true  (case-insensitive)
 *  - String 'false','0','no','n','off'     → false (case-insensitive)
 *  - Anything else                         → empty {}
 *  - Empty input                           → empty {}
 *
 * The static tryConvert() helper is shared with convertsToBoolean().
 *
 * @author FHIR Tools Contributors
 */
final class ToBooleanFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toBoolean');
    }

    /**
     * Execute the toBoolean conversion on the input collection.
     *
     * Converts the first item in the input to a boolean according to FHIRPath
     * conversion rules (accepts booleans, 0/1, and various string representations).
     *
     * @param Collection        $input      The input collection (uses first item)
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the boolean, or empty if conversion fails
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for toBoolean)
        $this->validateParameterCount($parameters, 0);
        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $result = self::tryConvert($input->first());

        return $result !== null ? Collection::single($result) : Collection::empty();
    }

    /**
     * Attempt to convert a value to boolean.
     *
     * This method handles different data types according to FHIRPath specification:
     *   - Booleans: returned as-is
     *   - Integers: only 1→true and 0→false (other integers fail)
     *   - Strings: recognizes common boolean representations (case-insensitive)
     *     - Truthy: 'true', '1', 'yes', 'y', 'on'
     *     - Falsy: 'false', '0', 'no', 'n', 'off'
     *
     * Returns null when the value cannot be converted (which becomes empty {} in FHIRPath).
     * Used by convertsToBoolean() to check convertibility without throwing.
     *
     * @param mixed $value The value to convert to boolean
     *
     * @return bool|null The boolean value or null if conversion fails
     */
    public static function tryConvert(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            // Only 1 and 0 are valid integer representations of booleans
            if ($value === 1) {
                return true;
            }

            if ($value === 0) {
                return false;
            }

            // Other integers (2, 3, -1, etc.) cannot be converted to boolean
            return null;
        }

        if (is_string($value)) {
            $lower = strtolower(trim($value));

            // Truthy string values (FHIRPath spec + common conventions)
            if (in_array($lower, ['true', '1', 'yes', 'y', 'on'], true)) {
                return true;
            }

            // Falsy string values (FHIRPath spec + common conventions)
            if (in_array($lower, ['false', '0', 'no', 'n', 'off'], true)) {
                return false;
            }

            return null;
        }

        return null;
    }
}
