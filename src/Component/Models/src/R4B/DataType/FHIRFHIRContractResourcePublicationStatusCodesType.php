<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContractResourcePublicationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRContractResourcePublicationStatusCodes
 *
 * @description Code type wrapper for FHIRContractResourcePublicationStatusCodes enum
 */
class FHIRFHIRContractResourcePublicationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRContractResourcePublicationStatusCodes|string|null $value The code value */
        public FHIRFHIRContractResourcePublicationStatusCodes|string|null $value = null,
    ) {
    }
}
