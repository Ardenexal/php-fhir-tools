<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: AllergyIntolerance Verification Status
 * URL: http://hl7.org/fhir/ValueSet/allergyintolerance-verification
 * Version: 5.0.0
 * Description: The verification status to support or decline the clinical status of the allergy or intolerance.
 */
enum AllergyIntoleranceVerificationStatus: string
{
    /** Unconfirmed */
    case unconfirmed = 'unconfirmed';

    /** Confirmed */
    case confirmed = 'confirmed';

    /** Refuted */
    case refuted = 'refuted';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
