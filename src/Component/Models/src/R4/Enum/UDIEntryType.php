<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: UDIEntryType
 * URL: http://hl7.org/fhir/ValueSet/udi-entry-type
 * Version: 4.0.1
 * Description: Codes to identify how UDI data was entered.
 */
enum UDIEntryType: string
{
    /** Barcode */
    case barcode = 'barcode';

    /** RFID */
    case rfid = 'rfid';

    /** Manual */
    case manual = 'manual';

    /** Card */
    case card = 'card';

    /** Self Reported */
    case selfreported = 'self-reported';

    /** Unknown */
    case unknown = 'unknown';
}
