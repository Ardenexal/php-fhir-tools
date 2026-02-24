<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toInteger(): Integer
 *
 * Converts the input value to an integer. Returns empty if the value cannot be
 * meaningfully converted.
 *
 * Conversion rules per the FHIRPath spec §5.5:
 *  - Integer                               → pass-through (already an integer)
 *  - Decimal with no fractional part       → integer (e.g. 42.0 → 42)
 *  - Numeric string (integer-valued)       → integer (e.g. '42' → 42)
 *  - Boolean true / false                  → 1 / 0
 *  - String 'true' / 'false'               → 1 / 0 (case-insensitive)
 *  - Anything else                         → empty {}
 *  - Empty input                           → empty {}
 *
 * Examples:
 *   - (42).toInteger() → 42
 *   - (42.0).toInteger() → 42  (no fractional part, converts successfully)
 *   - (42.5).toInteger() → {}  (has fractional part, cannot convert)
 *   - true.toInteger() → 1
 *   - false.toInteger() → 0
 *   - 'true'.toInteger() → 1
 *   - '42'.toInteger() → 42
 *   - '42.5'.toInteger() → {}  (decimal string cannot convert)
 *
 * The static tryConvert() helper is shared with convertsToInteger().
 *
 * @author FHIR Tools Contributors
 */
final class ToIntegerFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toInteger');
    }

    /**
     * Execute the toInteger conversion on the input collection.
     *
     * Converts the first item in the input to an integer according to FHIRPath
     * conversion rules (only converts values without fractional parts).
     *
     * @param Collection        $input      The input collection (uses first item)
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the integer, or empty if conversion fails
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for toInteger)
        $this->validateParameterCount($parameters, 0);
        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $result = self::tryConvert($input->first());

        return $result !== null ? Collection::single($result) : Collection::empty();
    }

    /**
     * Attempt to convert a value to integer.
     *
     * This method handles different data types according to FHIRPath specification:
     *   - Integers: returned as-is
     *   - Booleans: true→1, false→0
     *   - Floats: only if no fractional part (e.g., 42.0→42, but 42.5 fails)
     *   - Strings: numeric strings like '42' or boolean strings 'true'/'false'
     *
     * Returns null when the value cannot be converted (which becomes empty {} in FHIRPath).
     * Used by convertsToInteger() to check convertibility without throwing.
     *
     * @param mixed $value The value to convert to integer
     *
     * @return int|null The integer value or null if conversion fails
     */
    public static function tryConvert(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_bool($value)) {
            // FHIRPath spec: true = 1, false = 0
            return $value ? 1 : 0;
        }

        if (is_float($value)) {
            // Only convert if there is no fractional part
            // E.g., 42.0 → 42 (OK), but 42.5 → null (cannot convert)
            if (floor($value) === $value && !is_nan($value) && !is_infinite($value)) {
                return (int) $value;
            }

            return null;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            // Boolean string literals (case-insensitive)
            if (strtolower($trimmed) === 'true') {
                return 1;  // 'true' converts to integer 1
            }

            if (strtolower($trimmed) === 'false') {
                return 0;  // 'false' converts to integer 0
            }

            // Plain integer string (no decimal point, no exponent)
            // Pattern: optional minus sign, followed by one or more digits
            if (preg_match('/^-?[0-9]+$/', $trimmed) === 1) {
                return (int) $trimmed;
            }

            return null;
        }

        return null;
    }
}
