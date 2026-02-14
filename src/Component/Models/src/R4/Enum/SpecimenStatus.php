<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SpecimenStatus
 * URL: http://hl7.org/fhir/ValueSet/specimen-status
 * Version: 4.0.1
 * Description: Codes providing the status/availability of a specimen.
 */
enum SpecimenStatus: string
{
    /** Available */
    case available = 'available';

    /** Unavailable */
    case unavailable = 'unavailable';

    /** Unsatisfactory */
    case unsatisfactory = 'unsatisfactory';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
