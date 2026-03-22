<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AllergyIntolerance Clinical Status Codes
 * URL: http://hl7.org/fhir/ValueSet/allergyintolerance-clinical
 * Version: 4.0.1
 * Description: Preferred value set for AllergyIntolerance Clinical Status.
 */
enum AllergyIntoleranceClinicalStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';
}
