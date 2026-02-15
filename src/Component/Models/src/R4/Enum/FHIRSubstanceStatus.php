<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: FHIRSubstanceStatus
 * URL: http://hl7.org/fhir/ValueSet/substance-status
 * Version: 4.0.1
 * Description: A code to indicate if the substance is actively used.
 */
enum FHIRSubstanceStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
