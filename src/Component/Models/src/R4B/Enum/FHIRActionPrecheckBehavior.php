<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ActionPrecheckBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-precheck-behavior
 * Version: 4.0.1
 * Description: Defines selection frequency behavior for an action or group.
 */
enum FHIRActionPrecheckBehavior: string
{
    /** Yes */
    case yes = 'yes';

    /** No */
    case no = 'no';
}
