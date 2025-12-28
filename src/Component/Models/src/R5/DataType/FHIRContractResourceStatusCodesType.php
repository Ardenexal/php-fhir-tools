<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContractResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContractResourceStatusCodes
 *
 * @description Code type wrapper for FHIRContractResourceStatusCodes enum
 */
class FHIRContractResourceStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRContractResourceStatusCodes|string|null $value The code value */
        public FHIRContractResourceStatusCodes|string|null $value = null,
    ) {
    }
}
