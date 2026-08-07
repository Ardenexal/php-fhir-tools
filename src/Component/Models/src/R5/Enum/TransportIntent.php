<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Transport Intent
 * URL: http://hl7.org/fhir/ValueSet/transport-intent
 * Version: 5.0.0
 * Description: Distinguishes whether the transport is a proposal, plan or full order.
 */
enum TransportIntent: string
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
