<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * DateTimeImmutable subclass that carries the original FHIR partial-date format string.
 *
 * Used when deserializing partial FHIR dateTime values (YYYY, YYYY-MM, YYYY-MM-DD) so
 * that the original precision can be preserved on serialization — e.g. "2002" round-trips
 * as "2002", not "2002-01-01T00:00:00+00:00".
 */
final class FHIRDateTimeValue extends \DateTimeImmutable
{
    /** The PHP date() format string reflecting the original FHIR partial-date precision. */
    public string $originalFormat = \DateTimeInterface::ATOM;

    /**
     * Create a FHIRDateTimeValue from a partial FHIR date string, setting $originalFormat atomically.
     *
     * @param \DateTimeZone|null $timezone
     *
     * @return self|false Returns false if the value does not match $phpFormat
     */
    public static function fromPartialDate(string $datetime, string $phpFormat, ?\DateTimeZone $timezone = null): self|false
    {
        $instance = self::createFromFormat('!' . $phpFormat, $datetime, $timezone);
        if ($instance === false) {
            return false;
        }
        $instance->originalFormat = $phpFormat;

        return $instance;
    }
}
