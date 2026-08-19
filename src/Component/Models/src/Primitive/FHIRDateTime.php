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
        private Year|YearMonth|LocalDate|ZonedDateTime|null $value,
        private string $originalString,
        private ?string $parseError = null,
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

        try {
            return new self(ZonedDateTime::parse($raw), $raw);
        } catch (\Throwable $e) {
            // Leniently handle dateTime strings that omit the timezone offset
            // (e.g. "2020-11-11T10:58:14.768528"). FHIR R4 requires a timezone for
            // full dateTime, but some real-world data omits it; treat as UTC and
            // preserve the original string for round-trip fidelity.
            //
            // The retry is scoped to values that carry no offset at all, and the ORIGINAL
            // exception is rethrown when it fails. An unscoped retry appended "Z" to a value that
            // already had an offset, so the surfaced message named a string that was never in the
            // document ("…T12:59:60+10:00Z", two offsets) and misdirected anyone debugging any
            // dateTime failure.
            if (preg_match('/(?:Z|[+\-]\d{2}:\d{2})$/', $raw) === 1) {
                throw $e;
            }

            try {
                return new self(ZonedDateTime::parse($raw . 'Z'), $raw);
            } catch (\Throwable) {
                throw $e;
            }
        }
    }

    public static function unparsed(string $raw, string $error): static
    {
        return new self(null, $raw, $error);
    }

    public function getValue(): Year|YearMonth|LocalDate|ZonedDateTime|null
    {
        return $this->value;
    }

    public function getParseError(): ?string
    {
        return $this->parseError;
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
