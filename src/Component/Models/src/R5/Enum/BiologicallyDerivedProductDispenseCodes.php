<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: BiologicallyDerivedProductDispense Status Codes
 * URL: http://hl7.org/fhir/ValueSet/biologicallyderivedproductdispense-status
 * Version: 5.0.0
 * Description: BiologicallyDerivedProductDispense Status Codes
 */
enum BiologicallyDerivedProductDispenseCodes: string
{
    /** Preparation */
    case preparation = 'preparation';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Allocated */
    case allocated = 'allocated';

    /** Issued */
    case issued = 'issued';

    /** Unfulfilled */
    case unfulfilled = 'unfulfilled';

    /** Returned */
    case returned = 'returned';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
