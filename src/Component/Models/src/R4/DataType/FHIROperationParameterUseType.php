<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIROperationParameterUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROperationParameterUse
 *
 * @description Code type wrapper for FHIROperationParameterUse enum
 */
class FHIROperationParameterUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIROperationParameterUse|string|null $value The code value */
        public FHIROperationParameterUse|string|null $value = null,
    ) {
    }
}
