<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Specimen Contained Preference
 * URL: http://hl7.org/fhir/ValueSet/specimen-contained-preference
 * Version: 5.0.0
 * Description: Degree of preference of a type of conditioned specimen.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/specimen-contained-preference', version: '5.0.0')]
enum SpecimenContainedPreference: string
{
    /** Preferred */
    case preferred = 'preferred';

    /** Alternate */
    case alternate = 'alternate';
}
