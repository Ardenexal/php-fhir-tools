<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SupplyDeliveryStatus
 * URL: http://hl7.org/fhir/ValueSet/supplydelivery-status
 * Version: 4.0.1
 * Description: Status of the supply delivery.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/supplydelivery-status', version: '4.0.1')]
enum SupplyDeliveryStatus: string
{
    /** In Progress */
    case inprogress = 'in-progress';

    /** Delivered */
    case delivered = 'completed';

    /** Abandoned */
    case abandoned = 'abandoned';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
