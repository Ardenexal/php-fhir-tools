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
        /** @param FHIRBiologicallyDerivedProductDispenseCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
