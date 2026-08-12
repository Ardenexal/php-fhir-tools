<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ExplanationOfBenefitStatus
 * URL: http://hl7.org/fhir/ValueSet/explanationofbenefit-status
 * Version: 4.3.0
 * Description: A code specifying the state of the resource instance.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/explanationofbenefit-status', version: '4.3.0')]
enum ExplanationOfBenefitStatus: string
{
    /** Active */
    case active = 'active';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Draft */
    case draft = 'draft';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
