<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ExplanationOfBenefitStatus
 * URL: http://hl7.org/fhir/ValueSet/explanationofbenefit-status
 * Version: 4.0.1
 * Description: A code specifying the state of the resource instance.
 */
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
