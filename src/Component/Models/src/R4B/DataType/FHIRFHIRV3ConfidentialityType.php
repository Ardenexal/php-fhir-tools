<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRV3Confidentiality;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRV3Confidentiality
 *
 * @description Code type wrapper for FHIRV3Confidentiality enum
 */
class FHIRFHIRV3ConfidentialityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRV3Confidentiality|string|null $value The code value */
        public FHIRFHIRV3Confidentiality|string|null $value = null,
    ) {
    }
}
