<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: FHIRDeviceStatus
 * URL: http://hl7.org/fhir/ValueSet/device-status
 * Version: 4.0.1
 * Description: The availability status of the device.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/device-status', version: '4.0.1')]
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
