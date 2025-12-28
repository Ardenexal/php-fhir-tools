<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCodeSystemHierarchyMeaning;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSystemHierarchyMeaning
 *
 * @description Code type wrapper for FHIRCodeSystemHierarchyMeaning enum
 */
class FHIRFHIRCodeSystemHierarchyMeaningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCodeSystemHierarchyMeaning|string|null $value The code value */
        public FHIRFHIRCodeSystemHierarchyMeaning|string|null $value = null,
    ) {
    }
}
