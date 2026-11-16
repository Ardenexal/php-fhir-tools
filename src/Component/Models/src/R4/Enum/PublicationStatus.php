<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: PublicationStatus
 * URL: http://hl7.org/fhir/ValueSet/publication-status
 * Version: 4.0.1
 * Description: The lifecycle status of an artifact.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/publication-status', version: '4.0.1')]
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
