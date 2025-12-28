<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConformanceExpectation;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConformanceExpectation
 *
 * @description Code type wrapper for FHIRConformanceExpectation enum
 */
class FHIRConformanceExpectationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConformanceExpectation|string|null $value The code value */
        public FHIRConformanceExpectation|string|null $value = null,
    ) {
    }
}
