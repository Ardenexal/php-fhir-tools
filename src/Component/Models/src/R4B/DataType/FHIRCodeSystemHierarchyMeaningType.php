<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSystemHierarchyMeaning;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSystemHierarchyMeaning
 *
 * @description Code type wrapper for FHIRCodeSystemHierarchyMeaning enum
 */
class FHIRCodeSystemHierarchyMeaningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCodeSystemHierarchyMeaning|string|null $value The code value */
        public FHIRCodeSystemHierarchyMeaning|string|null $value = null,
    ) {
    }
}
