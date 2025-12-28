<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConformanceExpectation;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConformanceExpectation
 *
 * @description Code type wrapper for FHIRConformanceExpectation enum
 */
class FHIRFHIRConformanceExpectationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConformanceExpectation|string|null $value The code value */
        public FHIRFHIRConformanceExpectation|string|null $value = null,
    ) {
    }
}
