<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Value object wrapping a FHIRPath time-only literal.
 *
 * Accepts time strings with or without the leading T prefix (e.g. "14:34:28" or "T14:34:28").
 * The T prefix is always normalised in on construction, so ->value, getValue(), and __toString()
 * always return the T-prefixed form (e.g. "T14:34:28").
 */
readonly class FHIRPathTime implements FHIRPathTemporalTypeInterface
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = str_starts_with($value, 'T') ? $value : 'T' . $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTemporalTypeName(): string
    {
        return 'time';
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
