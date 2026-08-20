<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Condition Verification Status
 * URL: http://hl7.org/fhir/ValueSet/condition-ver-status
 * Version: 5.0.0
 * Description: The verification status to support or decline the clinical status of the condition or diagnosis.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/condition-ver-status', version: '5.0.0')]
enum ConditionVerificationStatus: string
{
    /** Unconfirmed */
    case unconfirmed = 'unconfirmed';

    /** Provisional */
    case provisional = 'provisional';

    /** Differential */
    case differential = 'differential';

    /** Confirmed */
    case confirmed = 'confirmed';

    /** Refuted */
    case refuted = 'refuted';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
