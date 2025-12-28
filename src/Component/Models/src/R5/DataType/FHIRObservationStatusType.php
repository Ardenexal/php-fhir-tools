<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRObservationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRObservationStatus
 *
 * @description Code type wrapper for FHIRObservationStatus enum
 */
class FHIRObservationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRObservationStatus|string|null $value The code value */
        public FHIRObservationStatus|string|null $value = null,
    ) {
    }
}
