<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AllergyIntolerance Clinical Status Codes
 * URL: http://hl7.org/fhir/ValueSet/allergyintolerance-clinical
 * Version: 5.0.0
 * Description: Preferred value set for AllergyIntolerance Clinical Status.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/allergyintolerance-clinical', version: '5.0.0')]
enum AllergyIntoleranceClinicalStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Resolved */
    case resolved = 'resolved';
}
