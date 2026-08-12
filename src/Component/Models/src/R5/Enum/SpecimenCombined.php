<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Specimen Combined
 * URL: http://hl7.org/fhir/ValueSet/specimen-combined
 * Version: 5.0.0
 * Description: Codes providing the combined status of a specimen.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/specimen-combined', version: '5.0.0')]
enum SpecimenCombined: string
{
    /** Grouped */
    case grouped = 'grouped';

    /** Pooled */
    case pooled = 'pooled';
}
