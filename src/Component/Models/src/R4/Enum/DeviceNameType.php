<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DeviceNameType
 * URL: http://hl7.org/fhir/ValueSet/device-nametype
 * Version: 4.0.1
 * Description: The type of name the device is referred by.
 */
enum DeviceNameType: string
{
    /** UDI Label name */
    case udilabelname = 'udi-label-name';

    /** User Friendly name */
    case userfriendlyname = 'user-friendly-name';

    /** Patient Reported name */
    case patientreportedname = 'patient-reported-name';

    /** Manufacturer name */
    case manufacturername = 'manufacturer-name';

    /** Model name */
    case modelname = 'model-name';

    /** other */
    case other = 'other';
}
