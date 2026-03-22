<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EndpointStatus
 * URL: http://hl7.org/fhir/ValueSet/endpoint-status
 * Version: 4.0.1
 * Description: The status of the endpoint.
 */
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
