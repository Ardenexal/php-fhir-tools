<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContractResourcePublicationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContractResourcePublicationStatusCodes
 *
 * @description Code type wrapper for FHIRContractResourcePublicationStatusCodes enum
 */
class FHIRContractResourcePublicationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRContractResourcePublicationStatusCodes|string|null $value The code value */
        public FHIRContractResourcePublicationStatusCodes|string|null $value = null,
    ) {
    }
}
