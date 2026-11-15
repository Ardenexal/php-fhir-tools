<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: EndpointStatus
 * URL: http://hl7.org/fhir/ValueSet/endpoint-status
 * Version: 4.3.0
 * Description: The status of the endpoint.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/endpoint-status', version: '4.3.0')]
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

    /** Test */
    case test = 'test';
}
