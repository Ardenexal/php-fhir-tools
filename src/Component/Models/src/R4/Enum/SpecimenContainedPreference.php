<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SpecimenContainedPreference
 * URL: http://hl7.org/fhir/ValueSet/specimen-contained-preference
 * Version: 4.0.1
 * Description: Degree of preference of a type of conditioned specimen.
 */
enum SpecimenContainedPreference: string
{
    /** Preferred */
    case preferred = 'preferred';

    /** Alternate */
    case alternate = 'alternate';
}
