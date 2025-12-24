<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRObservationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRObservationStatus
 *
 * @description Code type wrapper for FHIRObservationStatus enum
 */
class FHIRFHIRObservationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRObservationStatus|string|null $value The code value */
        public FHIRFHIRObservationStatus|string|null $value = null,
    ) {
    }
}
