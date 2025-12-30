<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRV3Confidentiality|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
