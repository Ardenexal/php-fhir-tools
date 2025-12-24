<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFilterOperator;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRFilterOperator
 *
 * @description Code type wrapper for FHIRFilterOperator enum
 */
class FHIRFHIRFilterOperatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFilterOperator|string|null $value The code value */
        public FHIRFHIRFilterOperator|string|null $value = null,
    ) {
    }
}
