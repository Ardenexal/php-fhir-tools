<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ConsentState
 * URL: http://hl7.org/fhir/ValueSet/consent-state-codes
 * Version: 4.0.1
 * Description: Indicates the state of the consent.
 */
enum ConsentState: string
{
    /** Pending */
    case pending = 'draft';

    /** Proposed */
    case proposed = 'proposed';

    /** Active */
    case active = 'active';

    /** Rejected */
    case rejected = 'rejected';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
