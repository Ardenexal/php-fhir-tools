<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRBiologicallyDerivedProductDispenseCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductDispenseCodes
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductDispenseCodes enum
 */
class FHIRBiologicallyDerivedProductDispenseCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRBiologicallyDerivedProductDispenseCodes|string|null $value The code value */
        public FHIRBiologicallyDerivedProductDispenseCodes|string|null $value = null,
    ) {
    }
}
