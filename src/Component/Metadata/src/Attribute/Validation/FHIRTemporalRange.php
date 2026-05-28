<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a property with FHIR temporal range bounds from minValue[x] / maxValue[x]
 * StructureDefinition fields for date, dateTime, instant, and time types.
 *
 * Partial dates (YYYY or YYYY-MM) are expanded to period boundaries before comparison:
 * the min-side expands to the start of the period; the max-side to the end.
 *
 * See ADR-006 for the chosen mixed-precision comparison strategy.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRTemporalRange extends Constraint
{
    /**
     * @param string|null $minValue     Inclusive lower bound in the native FHIR string format
     * @param string|null $maxValue     Inclusive upper bound in the native FHIR string format
     * @param string      $temporalType 'date'|'dateTime'|'instant'|'time'
     */
    public function __construct(
        public readonly ?string $minValue,
        public readonly ?string $maxValue,
        public readonly string $temporalType,
    ) {
        parent::__construct();
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
