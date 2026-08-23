<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: RequestStatus
 * URL: http://hl7.org/fhir/ValueSet/request-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of a request.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/request-status', version: '5.0.0')]
enum RequestStatus: string
{
    /** Draft */
    case draft = 'draft';

    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'on-hold';

    /** Revoked */
    case revoked = 'revoked';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
