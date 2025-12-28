<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQualityType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQualityType
 *
 * @description Code type wrapper for FHIRQualityType enum
 */
class FHIRFHIRQualityTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQualityType|string|null $value The code value */
        public FHIRFHIRQualityType|string|null $value = null,
    ) {
    }
}
