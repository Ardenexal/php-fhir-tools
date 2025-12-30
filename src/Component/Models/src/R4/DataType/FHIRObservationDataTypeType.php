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
        /** @param FHIRObservationDataType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
