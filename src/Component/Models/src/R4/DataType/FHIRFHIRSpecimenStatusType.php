<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSpecimenStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenStatus
 *
 * @description Code type wrapper for FHIRSpecimenStatus enum
 */
class FHIRFHIRSpecimenStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSpecimenStatus|string|null $value The code value */
        public FHIRFHIRSpecimenStatus|string|null $value = null,
    ) {
    }
}
