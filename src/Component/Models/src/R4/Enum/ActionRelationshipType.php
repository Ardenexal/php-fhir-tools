<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ActionRelationshipType
 * URL: http://hl7.org/fhir/ValueSet/action-relationship-type
 * Version: 4.0.1
 * Description: Defines the types of relationships between actions.
 */
enum ActionRelationshipType: string
{
    /** Before Start */
    case beforestart = 'before-start';

    /** Before */
    case before = 'before';

    /** Before End */
    case beforeend = 'before-end';

    /** Concurrent With Start */
    case concurrentwithstart = 'concurrent-with-start';

    /** Concurrent */
    case concurrent = 'concurrent';

    /** Concurrent With End */
    case concurrentwithend = 'concurrent-with-end';

    /** After Start */
    case afterstart = 'after-start';

    /** After */
    case after = 'after';

    /** After End */
    case afterend = 'after-end';
}
