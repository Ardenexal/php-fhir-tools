<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRSubstanceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRSubstanceStatus
 *
 * @description Code type wrapper for FHIRFHIRSubstanceStatus enum
 */
class FHIRFHIRFHIRSubstanceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRSubstanceStatus|string|null $value The code value */
        public FHIRFHIRFHIRSubstanceStatus|string|null $value = null,
    ) {
    }
}
