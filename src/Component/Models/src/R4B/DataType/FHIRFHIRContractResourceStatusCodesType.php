<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContractResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRContractResourceStatusCodes
 *
 * @description Code type wrapper for FHIRContractResourceStatusCodes enum
 */
class FHIRFHIRContractResourceStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRContractResourceStatusCodes|string|null $value The code value */
        public FHIRFHIRContractResourceStatusCodes|string|null $value = null,
    ) {
    }
}
