<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Brick\DateTime\Instant;
use Brick\DateTime\ZonedDateTime;

/**
 * FHIR instant value object.
 *
 * Wraps a UTC Instant (epoch seconds + nanos). The original string is preserved so
 * that the timezone offset from the source can be round-tripped — FHIR requires an
 * offset on instant values, and converting to pure UTC would silently lose that.
 */
final readonly class FHIRInstant implements FHIRTemporalValue
{
    private function __construct(
        private Instant $value,
        private string $originalString,
    ) {
    }

    public static function parse(string $raw): static
    {
        $zdt = ZonedDateTime::parse($raw);

        return new self($zdt->getInstant(), $raw);
    }

    public function getValue(): Instant
    {
        return $this->value;
    }

    /**
     * Returns the original string to preserve the timezone offset.
     * Falls back to UTC rendering only when unavailable.
     */
    public function __toString(): string
    {
        return $this->originalString;
    }
}
