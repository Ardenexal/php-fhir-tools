<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CharacteristicCombination
 * URL: http://hl7.org/fhir/ValueSet/characteristic-combination
 * Version: 4.3.0
 * Description: Logical grouping of characteristics.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/characteristic-combination', version: '4.3.0')]
enum CharacteristicCombination: string
{
    /** intersection */
    case intersection = 'intersection';

    /** union */
    case union = 'union';
}
