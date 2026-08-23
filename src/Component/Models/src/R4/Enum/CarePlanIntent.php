<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Care Plan Intent
 * URL: http://hl7.org/fhir/ValueSet/care-plan-intent
 * Version: 4.0.1
 * Description: Codes indicating the degree of authority/intentionality associated with a care plan.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/care-plan-intent', version: '4.0.1')]
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

    /** Original Order */
    case originalorder = 'original-order';

    /** Reflex Order */
    case reflexorder = 'reflex-order';

    /** Filler Order */
    case fillerorder = 'filler-order';

    /** Instance Order */
    case instanceorder = 'instance-order';

    /** Option */
    case option = 'option';
}
