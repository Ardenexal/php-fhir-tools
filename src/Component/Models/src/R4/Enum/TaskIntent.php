<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TaskIntent
 * URL: http://hl7.org/fhir/ValueSet/task-intent
 * Version: 4.0.1
 * Description: Distinguishes whether the task is a proposal, plan or full order.
 */
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

    /** Option */
    case option = 'option';

    /** original-order */
    case originalorder = 'original-order';

    /** reflex-order */
    case reflexorder = 'reflex-order';

    /** filler-order */
    case fillerorder = 'filler-order';

    /** instance-order */
    case instanceorder = 'instance-order';
}
