<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: FHIR Device Status
 * URL: http://hl7.org/fhir/ValueSet/device-status
 * Version: 5.0.0
 * Description: The status of the Device record.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/device-status', version: '5.0.0')]
enum FHIRDeviceStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
