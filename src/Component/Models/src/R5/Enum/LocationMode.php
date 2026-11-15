<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Location Mode
 * URL: http://hl7.org/fhir/ValueSet/location-mode
 * Version: 5.0.0
 * Description: Indicates whether a resource instance represents a specific location or a class of locations.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/location-mode', version: '5.0.0')]
enum LocationMode: string
{
    /** Instance */
    case instance = 'instance';

    /** Kind */
    case kind = 'kind';
}
