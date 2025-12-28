<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSearchParamType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchParamType
 *
 * @description Code type wrapper for FHIRSearchParamType enum
 */
class FHIRSearchParamTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSearchParamType|string|null $value The code value */
        public FHIRSearchParamType|string|null $value = null,
    ) {
    }
}
