<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: LocationMode
 * URL: http://hl7.org/fhir/ValueSet/location-mode
 * Version: 4.0.1
 * Description: Indicates whether a resource instance represents a specific location or a class of locations.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/location-mode', version: '4.0.1')]
enum LocationMode: string
{
    /** Instance */
    case instance = 'instance';

    /** Kind */
    case kind = 'kind';
}
