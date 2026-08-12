<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Location Status
 * URL: http://hl7.org/fhir/ValueSet/location-status
 * Version: 5.0.0
 * Description: Indicates whether the location is still in use.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/location-status', version: '5.0.0')]
enum LocationStatus: string
{
    /** Active */
    case active = 'active';

    /** Suspended */
    case suspended = 'suspended';

    /** Inactive */
    case inactive = 'inactive';
}
