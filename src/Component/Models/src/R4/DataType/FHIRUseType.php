<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUse
 *
 * @description Code type wrapper for FHIRUse enum
 */
class FHIRUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRUse|string|null $value The code value */
        public FHIRUse|string|null $value = null,
    ) {
    }
}
