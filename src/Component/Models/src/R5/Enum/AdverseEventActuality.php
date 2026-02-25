<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Adverse Event Actuality
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-actuality
 * Version: 5.0.0
 * Description: Overall nature of the adverse event, e.g. real or potential.
 */
enum AdverseEventActuality: string
{
    /** Adverse Event */
    case adverseevent = 'actual';

    /** Potential Adverse Event */
    case potentialadverseevent = 'potential';
}
