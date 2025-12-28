<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRBiologicallyDerivedProductDispenseCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductDispenseCodes
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductDispenseCodes enum
 */
class FHIRFHIRBiologicallyDerivedProductDispenseCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBiologicallyDerivedProductDispenseCodes|string|null $value The code value */
        public FHIRFHIRBiologicallyDerivedProductDispenseCodes|string|null $value = null,
    ) {
    }
}
