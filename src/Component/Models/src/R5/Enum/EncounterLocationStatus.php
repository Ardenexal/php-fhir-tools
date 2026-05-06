<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Encounter Location Status
 * URL: http://hl7.org/fhir/ValueSet/encounter-location-status
 * Version: 5.0.0
 * Description: The status of the location.
 */
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
