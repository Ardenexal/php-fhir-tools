<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIdentifierUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIdentifierUse
 *
 * @description Code type wrapper for FHIRIdentifierUse enum
 */
class FHIRFHIRIdentifierUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIdentifierUse|string|null $value The code value */
        public FHIRFHIRIdentifierUse|string|null $value = null,
    ) {
    }
}
