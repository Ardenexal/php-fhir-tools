<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ActionPrecheckBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-precheck-behavior
 * Version: 4.0.1
 * Description: Defines selection frequency behavior for an action or group.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-precheck-behavior', version: '4.0.1')]
enum ActionPrecheckBehavior: string
{
    /** Yes */
    case yes = 'yes';

    /** No */
    case no = 'no';
}
