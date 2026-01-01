<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: ListStatus
 * URL: http://hl7.org/fhir/ValueSet/list-status
 * Version: 4.0.1
 * Description: The current state of the list.
 */
enum FHIRListStatus: string
{
    /** Current */
    case current = 'current';

    /** Retired */
    case retired = 'retired';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
