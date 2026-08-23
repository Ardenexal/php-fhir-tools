<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: strand Type
 * URL: http://hl7.org/fhir/ValueSet/strand-type
 * Version: 5.0.0
 * Description: Type for strand.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/strand-type', version: '5.0.0')]
enum StrandType: string
{
    /** Watson strand of starting sequence */
    case watsonstrandofstartingsequence = 'watson';

    /** Crick strand of starting sequence */
    case crickstrandofstartingsequence = 'crick';
}
