<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AllergyIntolerance Verification Status Codes
 * URL: http://hl7.org/fhir/ValueSet/allergyintolerance-verification
 * Version: 4.0.1
 * Description: Preferred value set for AllergyIntolerance Verification Status.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/allergyintolerance-verification', version: '4.0.1')]
enum AllergyIntoleranceVerificationStatusCodes: string
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
