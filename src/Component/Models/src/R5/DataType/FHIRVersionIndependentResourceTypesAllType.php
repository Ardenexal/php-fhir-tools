<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRVersionIndependentResourceTypesAll;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVersionIndependentResourceTypesAll
 *
 * @description Code type wrapper for FHIRVersionIndependentResourceTypesAll enum
 */
class FHIRVersionIndependentResourceTypesAllType extends FHIRCode
{
    public function __construct(
        /** @var FHIRVersionIndependentResourceTypesAll|string|null $value The code value */
        public FHIRVersionIndependentResourceTypesAll|string|null $value = null,
    ) {
    }
}
