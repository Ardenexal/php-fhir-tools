<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFilterOperator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFilterOperator
 *
 * @description Code type wrapper for FHIRFilterOperator enum
 */
class FHIRFilterOperatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFilterOperator|string|null $value The code value */
        public FHIRFilterOperator|string|null $value = null,
    ) {
    }
}
