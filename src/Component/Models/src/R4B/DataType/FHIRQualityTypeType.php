<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQualityType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQualityType
 *
 * @description Code type wrapper for FHIRQualityType enum
 */
class FHIRQualityTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRQualityType|string|null $value The code value */
        public FHIRQualityType|string|null $value = null,
    ) {
    }
}
