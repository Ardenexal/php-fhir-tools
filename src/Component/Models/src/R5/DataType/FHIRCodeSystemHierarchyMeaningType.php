<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRCodeSystemHierarchyMeaning|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
