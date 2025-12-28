<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRNameUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNameUse
 *
 * @description Code type wrapper for FHIRNameUse enum
 */
class FHIRFHIRNameUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNameUse|string|null $value The code value */
        public FHIRFHIRNameUse|string|null $value = null,
    ) {
    }
}
