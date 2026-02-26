<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Marker interface for FHIRPath date/time literal value objects.
 *
 * Implemented by FHIRPathDate, FHIRPathDateTime, and FHIRPathTime to carry
 * type information alongside their bare ISO string values. This allows a single
 * instanceof check to replace triple-instanceof checks across the evaluator,
 * while getTemporalTypeName() provides the specific FHIR type name for inferType().
 */
interface FHIRPathTemporalTypeInterface extends \Stringable
{
    /**
     * Returns the bare ISO string value (@ prefix already stripped).
     *
     * E.g. "2015", "2015-02-04T14:34:28+10:00", "T14:34:28"
     */
    public function getValue(): string;

    /**
     * Returns the FHIR type name for this temporal literal.
     *
     * One of: 'date', 'dateTime', 'time'
     */
    public function getTemporalTypeName(): string;
}
