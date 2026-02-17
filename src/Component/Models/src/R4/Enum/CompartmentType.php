<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CompartmentType
 * URL: http://hl7.org/fhir/ValueSet/compartment-type
 * Version: 4.0.1
 * Description: Which type a compartment definition describes.
 */
enum CompartmentType: string
{
    /** Patient */
    case patient = 'Patient';

    /** Encounter */
    case encounter = 'Encounter';

    /** RelatedPerson */
    case relatedperson = 'RelatedPerson';

    /** Practitioner */
    case practitioner = 'Practitioner';

    /** Device */
    case device = 'Device';
}
