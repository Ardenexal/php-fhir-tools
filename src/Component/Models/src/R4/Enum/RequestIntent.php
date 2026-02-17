<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: RequestIntent
 * URL: http://hl7.org/fhir/ValueSet/request-intent
 * Version: 4.0.1
 * Description: Codes indicating the degree of authority/intentionality associated with a request.
 */
enum RequestIntent: string
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
