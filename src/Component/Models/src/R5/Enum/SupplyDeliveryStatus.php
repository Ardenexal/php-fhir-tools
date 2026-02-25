<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Supply Delivery Status
 * URL: http://hl7.org/fhir/ValueSet/supplydelivery-status
 * Version: 5.0.0
 * Description: Status of the supply delivery.
 */
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
