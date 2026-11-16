<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SpecimenContainedPreference
 * URL: http://hl7.org/fhir/ValueSet/specimen-contained-preference
 * Version: 4.3.0
 * Description: Degree of preference of a type of conditioned specimen.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/specimen-contained-preference', version: '4.3.0')]
enum SpecimenContainedPreference: string
{
    /** Preferred */
    case preferred = 'preferred';

    /** Alternate */
    case alternate = 'alternate';
}
