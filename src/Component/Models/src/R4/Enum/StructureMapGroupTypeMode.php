<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: StructureMapGroupTypeMode
 * URL: http://hl7.org/fhir/ValueSet/map-group-type-mode
 * Version: 4.0.1
 * Description: If this is the default rule set to apply for the source type, or this combination of types.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/map-group-type-mode', version: '4.0.1')]
enum StructureMapGroupTypeMode: string
{
    /** Not a Default */
    case no_ta_default = 'none';

    /** Default for Type Combination */
    case defaultfortypecombination = 'types';
}
