<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIROperationParameterUse;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIROperationParameterUse
 *
 * @description Code type wrapper for FHIROperationParameterUse enum
 */
class FHIRFHIROperationParameterUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIROperationParameterUse|string|null $value The code value */
        public FHIRFHIROperationParameterUse|string|null $value = null,
    ) {
    }
}
