<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBiologicallyDerivedProductCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductCategory
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductCategory enum
 */
class FHIRBiologicallyDerivedProductCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRBiologicallyDerivedProductCategory|string|null $value The code value */
        public FHIRBiologicallyDerivedProductCategory|string|null $value = null,
    ) {
    }
}
