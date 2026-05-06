<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Action Relationship Type
 * URL: http://hl7.org/fhir/ValueSet/action-relationship-type
 * Version: 5.0.0
 * Description: Defines the types of relationships between actions.
 */
enum ActionRelationshipType: string
{
    /** Before */
    case before = 'before';

    /** Concurrent */
    case concurrent = 'concurrent';

    /** After */
    case after = 'after';
}
