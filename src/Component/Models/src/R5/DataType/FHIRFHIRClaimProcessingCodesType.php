<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRClaimProcessingCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRClaimProcessingCodes
 *
 * @description Code type wrapper for FHIRClaimProcessingCodes enum
 */
class FHIRFHIRClaimProcessingCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRClaimProcessingCodes|string|null $value The code value */
        public FHIRFHIRClaimProcessingCodes|string|null $value = null,
    ) {
    }
}
