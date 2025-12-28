<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRObservationDataType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRObservationDataType
 *
 * @description Code type wrapper for FHIRObservationDataType enum
 */
class FHIRFHIRObservationDataTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRObservationDataType|string|null $value The code value */
        public FHIRFHIRObservationDataType|string|null $value = null,
    ) {
    }
}
