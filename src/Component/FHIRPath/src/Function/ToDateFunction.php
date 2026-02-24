<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toDate(): Date
 *
 * Converts the input value to a FHIRPath Date string. Accepts partial ISO 8601
 * date strings (YYYY, YYYY-MM, YYYY-MM-DD) and full datetime strings (time and
 * timezone components are stripped). Returns empty {} on failure or empty input.
 *
 * FHIRPath dates are represented internally as strings without the '@' prefix.
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ToDateFunction extends AbstractFunction
{
    /** Matches YYYY, YYYY-MM, or YYYY-MM-DD optionally followed by a time portion */
    private const DATE_PATTERN = '/^\d{4}(?:-(?:0[1-9]|1[0-2])(?:-(?:0[1-9]|[12]\d|3[01]))?)?(?:T.*)?$/';

    public function __construct()
    {
        parent::__construct('toDate');
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
     * Attempt to convert a value to a FHIRPath date string (YYYY[-MM[-DD]]).
     *
     * Returns null when the value cannot be converted. DateTime inputs have their
     * time component stripped. Used by convertsToDate() to check convertibility.
     */
    public static function tryConvert(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        if (preg_match(self::DATE_PATTERN, $value) !== 1) {
            return null;
        }

        // Strip time component if present (everything from 'T' onward)
        $tPos = strpos($value, 'T');

        return $tPos !== false ? substr($value, 0, $tPos) : $value;
    }
}
