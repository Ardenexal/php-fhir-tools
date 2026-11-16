<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ActionCardinalityBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-cardinality-behavior
 * Version: 4.0.1
 * Description: Defines behavior for an action or a group for how many times that item may be repeated.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-cardinality-behavior', version: '4.0.1')]
enum ActionCardinalityBehavior: string
{
    /** Single */
    case single = 'single';

    /** Multiple */
    case multiple = 'multiple';
}
