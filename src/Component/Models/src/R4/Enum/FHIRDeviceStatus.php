<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: FHIRDeviceStatus
 * URL: http://hl7.org/fhir/ValueSet/device-status
 * Version: 4.0.1
 * Description: The availability status of the device.
 */
enum FHIRDeviceStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
