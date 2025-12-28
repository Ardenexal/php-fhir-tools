<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRV3Confidentiality;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRV3Confidentiality
 *
 * @description Code type wrapper for FHIRV3Confidentiality enum
 */
class FHIRV3ConfidentialityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRV3Confidentiality|string|null $value The code value */
        public FHIRV3Confidentiality|string|null $value = null,
    ) {
    }
}
