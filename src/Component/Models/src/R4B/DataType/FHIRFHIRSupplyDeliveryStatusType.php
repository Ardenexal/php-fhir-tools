<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyDeliveryStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyDeliveryStatus
 *
 * @description Code type wrapper for FHIRSupplyDeliveryStatus enum
 */
class FHIRFHIRSupplyDeliveryStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSupplyDeliveryStatus|string|null $value The code value */
        public FHIRFHIRSupplyDeliveryStatus|string|null $value = null,
    ) {
    }
}
