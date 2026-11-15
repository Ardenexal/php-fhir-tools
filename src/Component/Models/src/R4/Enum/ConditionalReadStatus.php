<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConditionalReadStatus
 * URL: http://hl7.org/fhir/ValueSet/conditional-read-status
 * Version: 4.0.1
 * Description: A code that indicates how the server supports conditional read.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conditional-read-status', version: '4.0.1')]
enum ConditionalReadStatus: string
{
    /** Not Supported */
    case notsupported = 'not-supported';

    /** If-Modified-Since */
    case ifmodifiedsince = 'modified-since';

    /** If-None-Match */
    case ifnonematch = 'not-match';

    /** Full Support */
    case fullsupport = 'full-support';
}
