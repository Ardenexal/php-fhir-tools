<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductCategory
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductCategory enum
 */
class FHIRFHIRBiologicallyDerivedProductCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBiologicallyDerivedProductCategory|string|null $value The code value */
        public FHIRFHIRBiologicallyDerivedProductCategory|string|null $value = null,
    ) {
    }
}
