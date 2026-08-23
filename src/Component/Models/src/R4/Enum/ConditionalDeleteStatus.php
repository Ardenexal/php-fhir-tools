<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConditionalDeleteStatus
 * URL: http://hl7.org/fhir/ValueSet/conditional-delete-status
 * Version: 4.0.1
 * Description: A code that indicates how the server supports conditional delete.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conditional-delete-status', version: '4.0.1')]
enum ConditionalDeleteStatus: string
{
    /** Not Supported */
    case notsupported = 'not-supported';

    /** Single Deletes Supported */
    case singledeletessupported = 'single';

    /** Multiple Deletes Supported */
    case multipledeletessupported = 'multiple';
}
