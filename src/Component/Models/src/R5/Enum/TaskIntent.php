<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Task Intent
 * URL: http://hl7.org/fhir/ValueSet/task-intent
 * Version: 5.0.0
 * Description: Distinguishes whether the task is a proposal, plan or full order.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/task-intent', version: '5.0.0')]
enum TaskIntent: string
{
    /** Unknown */
    case unknown = 'unknown';

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
