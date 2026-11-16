<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Endpoint Status
 * URL: http://hl7.org/fhir/ValueSet/endpoint-status
 * Version: 5.0.0
 * Description: The status of the endpoint.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/endpoint-status', version: '5.0.0')]
enum EndpointStatus: string
{
    /** Active */
    case active = 'active';

    /** Suspended */
    case suspended = 'suspended';

    /** Error */
    case error = 'error';

    /** Off */
    case off = 'off';

    /** Entered in error */
    case enteredinerror = 'entered-in-error';
}
