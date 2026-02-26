<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Value object wrapping a FHIRPath date-only literal.
 *
 * Stores the bare ISO date string (@ prefix stripped, e.g. "2015", "2015-02", "2015-02-04")
 * and carries type information so inferType() can distinguish Date from plain strings.
 */
readonly class FHIRPathDate implements FHIRPathTemporalTypeInterface
{
    public function __construct(
        public string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTemporalTypeName(): string
    {
        return 'date';
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
