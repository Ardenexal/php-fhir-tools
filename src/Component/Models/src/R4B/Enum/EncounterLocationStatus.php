<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: EncounterLocationStatus
 * URL: http://hl7.org/fhir/ValueSet/encounter-location-status
 * Version: 4.3.0
 * Description: The status of the location.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/encounter-location-status', version: '4.3.0')]
enum EncounterLocationStatus: string
{
    /** Planned */
    case planned = 'planned';

    /** Active */
    case active = 'active';

    /** Reserved */
    case reserved = 'reserved';

    /** Completed */
    case completed = 'completed';
}
