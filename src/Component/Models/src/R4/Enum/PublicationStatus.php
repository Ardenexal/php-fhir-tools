<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: PublicationStatus
 * URL: http://hl7.org/fhir/ValueSet/publication-status
 * Version: 4.0.1
 * Description: The lifecycle status of an artifact.
 */
enum PublicationStatus: string
{
    /** Draft */
    case draft = 'draft';

    /** Active */
    case active = 'active';

    /** Retired */
    case retired = 'retired';

    /** Unknown */
    case unknown = 'unknown';
}
