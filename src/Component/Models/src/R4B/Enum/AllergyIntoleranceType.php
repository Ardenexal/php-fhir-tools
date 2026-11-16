<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AllergyIntoleranceType
 * URL: http://hl7.org/fhir/ValueSet/allergy-intolerance-type
 * Version: 4.3.0
 * Description: Identification of the underlying physiological mechanism for a Reaction Risk.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/allergy-intolerance-type', version: '4.3.0')]
enum AllergyIntoleranceType: string
{
    /** Allergy */
    case allergy = 'allergy';

    /** Intolerance */
    case intolerance = 'intolerance';
}
