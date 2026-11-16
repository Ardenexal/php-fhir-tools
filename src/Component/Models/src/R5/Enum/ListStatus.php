<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: List Status
 * URL: http://hl7.org/fhir/ValueSet/list-status
 * Version: 5.0.0
 * Description: The current state of the list.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/list-status', version: '5.0.0')]
enum ListStatus: string
{
    /** Current */
    case current = 'current';

    /** Retired */
    case retired = 'retired';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
