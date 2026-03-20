<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Brick\DateTime\LocalDate;
use Brick\DateTime\Year;
use Brick\DateTime\YearMonth;
use Brick\DateTime\ZonedDateTime;

/**
 * FHIR dateTime value object.
 *
 * Wraps Year, YearMonth, LocalDate, or ZonedDateTime depending on the precision of the value.
 * Partial forms (YYYY, YYYY-MM, YYYY-MM-DD) are valid; full form requires a timezone offset.
 */
final readonly class FHIRDateTime implements FHIRTemporalValue
{
    private function __construct(
        private Year|YearMonth|LocalDate|ZonedDateTime $value,
        private string $originalString,
    ) {
    }

    public static function parse(string $raw): static
    {
        if (preg_match('/^\d{4}$/', $raw)) {
            return new self(Year::parse($raw), $raw);
        }

        if (preg_match('/^\d{4}-\d{2}$/', $raw)) {
            return new self(YearMonth::parse($raw), $raw);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return new self(LocalDate::parse($raw), $raw);
        }

        return new self(ZonedDateTime::parse($raw), $raw);
    }

    public function getValue(): Year|YearMonth|LocalDate|ZonedDateTime
    {
        return $this->value;
    }

    /**
     * Returns the original string to preserve exact FHIR precision.
     * brick/date-time's ZonedDateTime::__toString() omits trailing :00 seconds,
     * which would break FHIR round-trip fidelity (e.g. "T14:58:00" → "T14:58").
     */
    public function __toString(): string
    {
        return $this->originalString;
    }
}
