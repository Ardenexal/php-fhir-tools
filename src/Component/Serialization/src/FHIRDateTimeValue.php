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
}
