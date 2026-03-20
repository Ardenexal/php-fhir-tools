<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

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
readonly class FHIRDateTime implements \Stringable
{
    private function __construct(
        private Year|YearMonth|LocalDate|ZonedDateTime $value,
    ) {
    }

    public static function parse(string $raw): self
    {
        if (preg_match('/^\d{4}$/', $raw)) {
            return new self(Year::parse($raw));
        }

        if (preg_match('/^\d{4}-\d{2}$/', $raw)) {
            return new self(YearMonth::parse($raw));
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return new self(LocalDate::parse($raw));
        }

        return new self(ZonedDateTime::parse($raw));
    }

    public function getValue(): Year|YearMonth|LocalDate|ZonedDateTime
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
