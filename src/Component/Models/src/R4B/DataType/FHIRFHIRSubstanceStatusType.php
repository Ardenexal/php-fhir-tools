<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubstanceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRSubstanceStatus
 *
 * @description Code type wrapper for FHIRFHIRSubstanceStatus enum
 */
class FHIRFHIRSubstanceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubstanceStatus|string|null $value The code value */
        public FHIRFHIRSubstanceStatus|string|null $value = null,
    ) {
    }
}
