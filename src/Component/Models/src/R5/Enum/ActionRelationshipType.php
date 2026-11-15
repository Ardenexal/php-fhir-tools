<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Action Relationship Type
 * URL: http://hl7.org/fhir/ValueSet/action-relationship-type
 * Version: 5.0.0
 * Description: Defines the types of relationships between actions.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-relationship-type', version: '5.0.0')]
enum ActionRelationshipType: string
{
    /** Before */
    case before = 'before';

    /** Before Start */
    case beforestart = 'before-start';

    /** Before End */
    case beforeend = 'before-end';

    /** Concurrent */
    case concurrent = 'concurrent';

    /** Concurrent With Start */
    case concurrentwithstart = 'concurrent-with-start';

    /** Concurrent With End */
    case concurrentwithend = 'concurrent-with-end';

    /** After */
    case after = 'after';

    /** After Start */
    case afterstart = 'after-start';

    /** After End */
    case afterend = 'after-end';
}
