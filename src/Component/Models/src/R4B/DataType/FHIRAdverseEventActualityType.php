<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventActuality;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventActuality
 *
 * @description Code type wrapper for FHIRAdverseEventActuality enum
 */
class FHIRAdverseEventActualityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAdverseEventActuality|string|null $value The code value */
        public FHIRAdverseEventActuality|string|null $value = null,
    ) {
    }
}
