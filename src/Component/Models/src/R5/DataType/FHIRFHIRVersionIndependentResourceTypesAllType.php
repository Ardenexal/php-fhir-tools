<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRVersionIndependentResourceTypesAll;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVersionIndependentResourceTypesAll
 *
 * @description Code type wrapper for FHIRVersionIndependentResourceTypesAll enum
 */
class FHIRFHIRVersionIndependentResourceTypesAllType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRVersionIndependentResourceTypesAll|string|null $value The code value */
        public FHIRFHIRVersionIndependentResourceTypesAll|string|null $value = null,
    ) {
    }
}
