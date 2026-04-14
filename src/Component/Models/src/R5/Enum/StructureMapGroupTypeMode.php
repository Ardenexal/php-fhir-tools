<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Map Group Type Mode
 * URL: http://hl7.org/fhir/ValueSet/map-group-type-mode
 * Version: 5.0.0
 * Description: If this is the default rule set to apply for the source type, or this combination of types.
 */
enum StructureMapGroupTypeMode: string
{
    /** Default for Type Combination */
    case defaultfortypecombination = 'types';
}
