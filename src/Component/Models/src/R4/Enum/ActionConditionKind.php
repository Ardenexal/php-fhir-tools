<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ActionConditionKind
 * URL: http://hl7.org/fhir/ValueSet/action-condition-kind
 * Version: 4.0.1
 * Description: Defines the kinds of conditions that can appear on actions.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-condition-kind', version: '4.0.1')]
enum ActionConditionKind: string
{
    /** Applicability */
    case applicability = 'applicability';

    /** Start */
    case start = 'start';

    /** Stop */
    case stop = 'stop';
}
