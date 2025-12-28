<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRClaimProcessingCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRClaimProcessingCodes
 *
 * @description Code type wrapper for FHIRClaimProcessingCodes enum
 */
class FHIRClaimProcessingCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRClaimProcessingCodes|string|null $value The code value */
        public FHIRClaimProcessingCodes|string|null $value = null,
    ) {
    }
}
