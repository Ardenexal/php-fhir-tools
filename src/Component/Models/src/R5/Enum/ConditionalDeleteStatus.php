<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Conditional Delete Status
 * URL: http://hl7.org/fhir/ValueSet/conditional-delete-status
 * Version: 5.0.0
 * Description: A code that indicates how the server supports conditional delete.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conditional-delete-status', version: '5.0.0')]
enum ConditionalDeleteStatus: string
{
    /** Not Supported */
    case notsupported = 'not-supported';

    /** Single Deletes Supported */
    case singledeletessupported = 'single';

    /** Multiple Deletes Supported */
    case multipledeletessupported = 'multiple';
}
