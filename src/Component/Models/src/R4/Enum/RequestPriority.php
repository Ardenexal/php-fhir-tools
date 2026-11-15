<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Request priority
 * URL: http://hl7.org/fhir/ValueSet/request-priority
 * Version: 4.0.1
 * Description: The clinical priority of a diagnostic order.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/request-priority', version: '4.0.1')]
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
