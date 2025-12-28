<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRV3ConfidentialityClassification;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRV3ConfidentialityClassification
 *
 * @description Code type wrapper for FHIRV3ConfidentialityClassification enum
 */
class FHIRFHIRV3ConfidentialityClassificationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRV3ConfidentialityClassification|string|null $value The code value */
        public FHIRFHIRV3ConfidentialityClassification|string|null $value = null,
    ) {
    }
}
