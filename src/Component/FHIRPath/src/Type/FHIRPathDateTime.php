<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Value object wrapping a FHIRPath datetime literal.
 *
 * Stores the bare ISO datetime string (@ prefix stripped, e.g. "2015-02-04T", "2015-02-04T14:34:28+10:00")
 * and carries type information so inferType() can distinguish DateTime from plain strings.
 */
readonly class FHIRPathDateTime implements FHIRPathTemporalTypeInterface
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
        return 'dateTime';
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Extracts the date portion of this datetime as a FHIRPathDate.
     * e.g. "2026-02-26T14:30:00+11:00" → FHIRPathDate("2026-02-26")
     */
    public function toDate(): FHIRPathDate
    {
        $datePart = strstr($this->value, 'T', before_needle: true);

        return new FHIRPathDate($datePart ?: $this->value);
    }

    /**
     * Extracts the time portion of this datetime as a FHIRPathTime (timezone stripped).
     * e.g. "2026-02-26T14:30:00+11:00" → FHIRPathTime("T14:30:00")
     */
    public function toTime(): FHIRPathTime
    {
        $pos = strpos($this->value, 'T');
        if ($pos === false) {
            return new FHIRPathTime('T00:00:00');
        }

        $timePart = substr($this->value, $pos);
        // Strip timezone offset (+HH:MM, -HH:MM, Z)
        $timePart = (string) preg_replace('/[+-]\d{2}:\d{2}$|Z$/', '', $timePart);

        return new FHIRPathTime($timePart ?: 'T00:00:00');
    }
}
