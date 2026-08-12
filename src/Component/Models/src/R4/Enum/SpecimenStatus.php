<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SpecimenStatus
 * URL: http://hl7.org/fhir/ValueSet/specimen-status
 * Version: 4.0.1
 * Description: Codes providing the status/availability of a specimen.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/specimen-status', version: '4.0.1')]
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
