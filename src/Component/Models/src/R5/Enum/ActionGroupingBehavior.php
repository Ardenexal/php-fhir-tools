<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Action Grouping Behavior
 * URL: http://hl7.org/fhir/ValueSet/action-grouping-behavior
 * Version: 5.0.0
 * Description: Defines organization behavior of a group.
 */
enum ActionGroupingBehavior: string
{
    /** Visual Group */
    case visualgroup = 'visual-group';

    /** Logical Group */
    case logicalgroup = 'logical-group';

    /** Sentence Group */
    case sentencegroup = 'sentence-group';
}
