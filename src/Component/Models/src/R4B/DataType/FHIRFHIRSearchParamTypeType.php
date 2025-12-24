<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchParamType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSearchParamType
 *
 * @description Code type wrapper for FHIRSearchParamType enum
 */
class FHIRFHIRSearchParamTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSearchParamType|string|null $value The code value */
        public FHIRFHIRSearchParamType|string|null $value = null,
    ) {
    }
}
