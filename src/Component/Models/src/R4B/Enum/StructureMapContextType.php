<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: StructureMapContextType
 * URL: http://hl7.org/fhir/ValueSet/map-context-type
 * Version: 4.3.0
 * Description: How to interpret the context.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/map-context-type', version: '4.3.0')]
enum StructureMapContextType: string
{
    /** Type */
    case type = 'type';

    /** Variable */
    case variable = 'variable';
}
