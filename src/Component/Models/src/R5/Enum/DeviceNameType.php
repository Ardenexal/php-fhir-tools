<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Device Name Type
 * URL: http://hl7.org/fhir/ValueSet/device-nametype
 * Version: 5.0.0
 * Description: The type of name the device is referred by.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/device-nametype', version: '5.0.0')]
enum DeviceNameType: string
{
    /** Registered name */
    case registeredname = 'registered-name';

    /** User Friendly name */
    case userfriendlyname = 'user-friendly-name';

    /** Patient Reported name */
    case patientreportedname = 'patient-reported-name';
}
