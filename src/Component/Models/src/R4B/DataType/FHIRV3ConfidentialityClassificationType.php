<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRV3ConfidentialityClassification;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRV3ConfidentialityClassification
 *
 * @description Code type wrapper for FHIRV3ConfidentialityClassification enum
 */
class FHIRV3ConfidentialityClassificationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRV3ConfidentialityClassification|string|null $value The code value */
        public FHIRV3ConfidentialityClassification|string|null $value = null,
    ) {
    }
}
