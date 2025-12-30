<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRBiologicallyDerivedProductCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
