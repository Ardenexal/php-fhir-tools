<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CompartmentType
 * URL: http://hl7.org/fhir/ValueSet/compartment-type
 * Version: 4.0.1
 * Description: Which type a compartment definition describes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/compartment-type', version: '4.0.1')]
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
