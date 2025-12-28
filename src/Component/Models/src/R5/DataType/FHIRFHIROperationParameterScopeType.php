<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIROperationParameterScope;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROperationParameterScope
 *
 * @description Code type wrapper for FHIROperationParameterScope enum
 */
class FHIRFHIROperationParameterScopeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIROperationParameterScope|string|null $value The code value */
        public FHIRFHIROperationParameterScope|string|null $value = null,
    ) {
    }
}
