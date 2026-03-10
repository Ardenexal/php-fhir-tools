<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Value object wrapping a FHIRPath decimal literal.
 *
 * Stores the exact decimal string (e.g. "1.2", "0.67", "3.0") so that
 * arithmetic and comparison can use bcmath for arbitrary-precision results,
 * avoiding IEEE 754 floating-point rounding errors inherent in PHP floats.
 *
 * Follows the same readonly pattern as FHIRPathDate/FHIRPathDateTime.
 */
readonly class FHIRPathDecimal implements \Stringable
{
    /**
     * Number of decimal places in the value string (0 for "2", 1 for "1.2", 2 for "0.67").
     */
    public int $precision;

    public function __construct(
        public string $value
    ) {
        $dotPos          = strpos($value, '.');
        $this->precision = $dotPos !== false ? strlen($value) - $dotPos - 1 : 0;
    }

    public function toFloat(): float
    {
        return (float) $this->value;
    }

    public function toInt(): int
    {
        return (int) $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
