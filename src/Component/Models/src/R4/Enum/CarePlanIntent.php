<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Care Plan Intent
 * URL: http://hl7.org/fhir/ValueSet/care-plan-intent
 * Version: 4.0.1
 * Description: Codes indicating the degree of authority/intentionality associated with a care plan.
 */
enum CarePlanIntent: string
{
    /** Proposal */
    case proposal = 'proposal';

    /** Plan */
    case plan = 'plan';

    /** Directive */
    case directive = 'directive';

    /** Order */
    case order = 'order';

    /** Option */
    case option = 'option';
}
