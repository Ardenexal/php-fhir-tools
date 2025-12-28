<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUse
 *
 * @description Code type wrapper for FHIRUse enum
 */
class FHIRFHIRUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRUse|string|null $value The code value */
        public FHIRFHIRUse|string|null $value = null,
    ) {
    }
}
