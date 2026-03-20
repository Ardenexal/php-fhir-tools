<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

use Brick\DateTime\LocalTime;

/**
 * FHIR time value object.
 *
 * Wraps a LocalTime — no date, no timezone. Exact semantic match for the FHIR time type.
 * Accepts an optional T prefix (used by FHIRPath literals such as @T14:30:00).
 */
readonly class FHIRTime implements \Stringable
{
    private function __construct(private LocalTime $value)
    {
    }

    public static function parse(string $raw): self
    {
        // Strip optional T prefix (FHIRPath time literals are prefixed with T)
        return new self(LocalTime::parse(ltrim($raw, 'T')));
    }

    public function getValue(): LocalTime
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
