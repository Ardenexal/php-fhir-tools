<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * toTime(): Time
 *
 * Converts the input value to a FHIRPath Time string. Accepts strings in the
 * format Thh, Thh:mm, Thh:mm:ss, or Thh:mm:ss.fff (the leading 'T' is
 * required by the FHIRPath spec). Returns empty {} on failure or empty input.
 *
 * FHIRPath times are represented internally as strings (e.g. 'T14:30:00').
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ToTimeFunction extends AbstractFunction
{
    /**
     * Matches Thh, Thh:mm, Thh:mm:ss, Thh:mm:ss.fff (T prefix optional for string conversion).
     * The FHIRPath spec accepts strings like '14', '14:34', '14:34:28' in addition to 'T14' etc.
     */
    private const TIME_PATTERN = '/^T?(?:[01]\d|2[0-3])(?::(?:[0-5]\d)(?::(?:[0-5]\d)(?:\.\d+)?)?)?$/';

    public function __construct()
    {
        parent::__construct('toTime');
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
     * Attempt to convert a value to a FHIRPath time string (Thh[:mm[:ss[.fff]]]).
     *
     * Returns null when the value cannot be converted. Used by convertsToTime().
     */
    public static function tryConvert(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        if (preg_match(self::TIME_PATTERN, $value) !== 1) {
            return null;
        }

        // Normalise to canonical FHIRPath time format: ensure T prefix is present
        return str_starts_with($value, 'T') ? $value : 'T' . $value;
    }
}
