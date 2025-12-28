<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContactPointUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContactPointUse
 *
 * @description Code type wrapper for FHIRContactPointUse enum
 */
class FHIRFHIRContactPointUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRContactPointUse|string|null $value The code value */
        public FHIRFHIRContactPointUse|string|null $value = null,
    ) {
    }
}
