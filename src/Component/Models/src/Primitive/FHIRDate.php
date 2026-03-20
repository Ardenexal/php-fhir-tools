<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Brick\DateTime\LocalDate;
use Brick\DateTime\Year;
use Brick\DateTime\YearMonth;

/**
 * FHIR date value object.
 *
 * Wraps a Year, YearMonth, or LocalDate depending on the precision of the FHIR date value.
 * FHIR date does not include a timezone.
 */
final readonly class FHIRDate implements FHIRTemporalValue
{
    private function __construct(
        private Year|YearMonth|LocalDate $value,
    ) {
    }

    public static function parse(string $raw): static
    {
        if (preg_match('/^\d{4}$/', $raw)) {
            return new self(Year::parse($raw));
        }

        if (preg_match('/^\d{4}-\d{2}$/', $raw)) {
            return new self(YearMonth::parse($raw));
        }

        return new self(LocalDate::parse($raw));
    }

    public function getValue(): Year|YearMonth|LocalDate
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
