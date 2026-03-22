<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ConditionalDeleteStatus
 * URL: http://hl7.org/fhir/ValueSet/conditional-delete-status
 * Version: 4.0.1
 * Description: A code that indicates how the server supports conditional delete.
 */
enum ConditionalDeleteStatus: string
{
    /** Not Supported */
    case notsupported = 'not-supported';

    /** Single Deletes Supported */
    case singledeletessupported = 'single';

    /** Multiple Deletes Supported */
    case multipledeletessupported = 'multiple';
}
