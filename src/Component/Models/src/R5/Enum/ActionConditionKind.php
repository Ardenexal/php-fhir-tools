<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Action Condition Kind
 * URL: http://hl7.org/fhir/ValueSet/action-condition-kind
 * Version: 5.0.0
 * Description: Defines the kinds of conditions that can appear on actions.
 */
enum ActionConditionKind: string
{
    /** Applicability */
    case applicability = 'applicability';

    /** Start */
    case start = 'start';

    /** Stop */
    case stop = 'stop';
}
