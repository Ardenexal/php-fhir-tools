<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Contract Resource Status Codes
 * URL: http://hl7.org/fhir/ValueSet/contract-status
 * Version: 4.0.1
 * Description: This value set contract specific codes for status.
 */
enum ContractResourceStatusCodes: string
{
    /** Amended */
    case amended = 'amended';

    /** Appended */
    case appended = 'appended';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Disputed */
    case disputed = 'disputed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Executable */
    case executable = 'executable';

    /** Executed */
    case executed = 'executed';

    /** Negotiable */
    case negotiable = 'negotiable';

    /** Offered */
    case offered = 'offered';

    /** Policy */
    case policy = 'policy';

    /** Rejected */
    case rejected = 'rejected';

    /** Renewed */
    case renewed = 'renewed';

    /** Revoked */
    case revoked = 'revoked';

    /** Resolved */
    case resolved = 'resolved';

    /** Terminated */
    case terminated = 'terminated';
}
