<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Guidance Response Status
 * URL: http://hl7.org/fhir/ValueSet/guidance-response-status
 * Version: 5.0.0
 * Description: The status of a guidance response.
 */
enum GuidanceResponseStatus: string
{
    /** Success */
    case success = 'success';

    /** Data Requested */
    case datarequested = 'data-requested';

    /** Data Required */
    case datarequired = 'data-required';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Failure */
    case failure = 'failure';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
