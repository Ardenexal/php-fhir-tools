<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSpecimenStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenStatus
 *
 * @description Code type wrapper for FHIRSpecimenStatus enum
 */
class FHIRSpecimenStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSpecimenStatus|string|null $value The code value */
        public FHIRSpecimenStatus|string|null $value = null,
    ) {
    }
}
