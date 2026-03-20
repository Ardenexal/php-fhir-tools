<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Brick\DateTime\LocalTime;

/**
 * FHIR time value object.
 *
 * Wraps a LocalTime — no date, no timezone. Exact semantic match for the FHIR time type.
 * Accepts an optional T prefix (used by FHIRPath literals such as @T14:30:00).
 */
final readonly class FHIRTime implements FHIRTemporalValue
{
    private function __construct(
        private LocalTime $value,
        private string $originalString,
    ) {
    }

    public static function parse(string $raw): static
    {
        // Strip optional T prefix (FHIRPath time literals are prefixed with T)
        return new self(LocalTime::parse(ltrim($raw, 'T')), $raw);
    }

    public function getValue(): LocalTime
    {
        return $this->value;
    }

    /**
     * Returns the original string to preserve exact FHIR precision.
     * brick/date-time's LocalTime::__toString() omits trailing :00 seconds,
     * which would break FHIR round-trip fidelity (e.g. "08:30:00" → "08:30").
     */
    public function __toString(): string
    {
        return $this->originalString;
    }
}
