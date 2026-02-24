<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toDateTime(): DateTime
 *
 * Converts the input value to a FHIRPath DateTime string. Accepts partial ISO
 * 8601 date/datetime strings:
 *   YYYY, YYYY-MM, YYYY-MM-DD
 *   YYYY-MM-DDThh:mm:ss[.fff][Z|(+|-)hh:mm]
 *
 * Returns empty {} on failure or empty input.
 *
 * FHIRPath datetimes are represented internally as strings without the '@' prefix.
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ToDateTimeFunction extends AbstractFunction
{
    /**
     * Matches FHIRPath datetime strings: partial date or full ISO-8601 datetime.
     * Allows YYYY, YYYY-MM, YYYY-MM-DD, and YYYY-MM-DDT... with optional timezone.
     */
    private const DATETIME_PATTERN =
        '/^\d{4}' .
        '(?:-(?:0[1-9]|1[0-2])' .
        '(?:-(?:0[1-9]|[12]\d|3[01])' .
        '(?:T(?:[01]\d|2[0-3])' .
        '(?::(?:[0-5]\d)' .
        '(?::(?:[0-5]\d)(?:\.\d+)?)?' .
        ')?' .
        '(?:Z|[+-](?:[01]\d|2[0-3]):[0-5]\d)?' .
        ')?' .
        ')?' .
        ')?$/';

    public function __construct()
    {
        parent::__construct('toDateTime');
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
     * Attempt to convert a value to a FHIRPath datetime string.
     *
     * Returns null when the value cannot be converted. Used by convertsToDateTime().
     */
    public static function tryConvert(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        if (preg_match(self::DATETIME_PATTERN, $value) !== 1) {
            return null;
        }

        return $value;
    }
}
