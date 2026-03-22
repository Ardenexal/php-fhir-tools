<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ConditionVerificationStatus
 * URL: http://hl7.org/fhir/ValueSet/condition-ver-status
 * Version: 4.0.1
 * Description: The verification status to support or decline the clinical status of the condition or diagnosis.
 */
enum ConditionVerificationStatus: string
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
