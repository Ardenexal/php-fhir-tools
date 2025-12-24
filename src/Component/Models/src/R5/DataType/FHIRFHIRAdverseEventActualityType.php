<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventActuality;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventActuality
 *
 * @description Code type wrapper for FHIRAdverseEventActuality enum
 */
class FHIRFHIRAdverseEventActualityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdverseEventActuality|string|null $value The code value */
        public FHIRFHIRAdverseEventActuality|string|null $value = null,
    ) {
    }
}
