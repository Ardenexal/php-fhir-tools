<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: FlagStatus
 * URL: http://hl7.org/fhir/ValueSet/flag-status
 * Version: 4.0.1
 * Description: Indicates whether this flag is active and needs to be displayed to a user, or whether it is no longer needed or was entered in error.
 */
enum FlagStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
