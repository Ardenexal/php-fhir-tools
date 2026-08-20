<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: LocationStatus
 * URL: http://hl7.org/fhir/ValueSet/location-status
 * Version: 4.0.1
 * Description: Indicates whether the location is still in use.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/location-status', version: '4.0.1')]
enum LocationStatus: string
{
    /** Active */
    case active = 'active';

    /** Suspended */
    case suspended = 'suspended';

    /** Inactive */
    case inactive = 'inactive';
}
