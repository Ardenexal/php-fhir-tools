<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: StructureMapContextType
 * URL: http://hl7.org/fhir/ValueSet/map-context-type
 * Version: 4.0.1
 * Description: How to interpret the context.
 */
enum StructureMapContextType: string
{
    /** Type */
    case type = 'type';

    /** Variable */
    case variable = 'variable';
}
