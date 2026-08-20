<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: FHIRSubstanceStatus
 * URL: http://hl7.org/fhir/ValueSet/substance-status
 * Version: 5.0.0
 * Description: A code to indicate if the substance is actively used.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/substance-status', version: '5.0.0')]
enum FHIRSubstanceStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
