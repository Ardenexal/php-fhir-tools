<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Device Corrective Action Scope
 * URL: http://hl7.org/fhir/ValueSet/device-correctiveactionscope
 * Version: 5.0.0
 * Description: Device - Corrective action scope
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/device-correctiveactionscope', version: '5.0.0')]
enum DeviceCorrectiveActionScope: string
{
    /** Model */
    case model = 'model';

    /** Lot Numbers */
    case lotnumbers = 'lot-numbers';

    /** Serial Numbers */
    case serialnumbers = 'serial-numbers';
}
