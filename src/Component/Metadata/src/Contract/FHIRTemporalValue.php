<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * Contract for FHIR temporal primitive value objects.
 * Implementations: FHIRDate, FHIRDateTime, FHIRTime, FHIRInstant.
 */
interface FHIRTemporalValue extends \Stringable
{
    /**
     * Parse a raw FHIR temporal string into the value object.
     *
     * @throws \Throwable on invalid input
     */
    public static function parse(string $raw): static;
}
