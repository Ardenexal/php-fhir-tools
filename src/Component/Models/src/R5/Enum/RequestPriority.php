<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: RequestPriority
 * URL: http://hl7.org/fhir/ValueSet/request-priority
 * Version: 5.0.0
 * Description: Identifies the level of importance to be assigned to actioning the request.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/request-priority', version: '5.0.0')]
enum RequestPriority: string
{
    /** Routine */
    case routine = 'routine';

    /** Urgent */
    case urgent = 'urgent';

    /** ASAP */
    case asap = 'asap';

    /** STAT */
    case stat = 'stat';
}
