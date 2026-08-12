<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ActionRequiredBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-required-behavior
 * Version: 4.3.0
 * Description: Defines expectations around whether an action or action group is required.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-required-behavior', version: '4.3.0')]
enum ActionRequiredBehavior: string
{
    /** Must */
    case must = 'must';

    /** Could */
    case could = 'could';

    /** Must Unless Documented */
    case mustunlessdocumented = 'must-unless-documented';
}
