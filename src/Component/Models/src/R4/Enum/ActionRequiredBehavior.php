<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ActionRequiredBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-required-behavior
 * Version: 4.0.1
 * Description: Defines expectations around whether an action or action group is required.
 */
enum ActionRequiredBehavior: string
{
    /** Must */
    case must = 'must';

    /** Could */
    case could = 'could';

    /** Must Unless Documented */
    case mustunlessdocumented = 'must-unless-documented';
}
