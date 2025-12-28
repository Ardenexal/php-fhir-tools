<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRObservationDataType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRObservationDataType
 *
 * @description Code type wrapper for FHIRObservationDataType enum
 */
class FHIRObservationDataTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRObservationDataType|string|null $value The code value */
        public FHIRObservationDataType|string|null $value = null,
    ) {
    }
}
