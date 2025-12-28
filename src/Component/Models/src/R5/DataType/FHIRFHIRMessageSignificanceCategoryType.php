<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMessageSignificanceCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMessageSignificanceCategory
 *
 * @description Code type wrapper for FHIRMessageSignificanceCategory enum
 */
class FHIRFHIRMessageSignificanceCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMessageSignificanceCategory|string|null $value The code value */
        public FHIRFHIRMessageSignificanceCategory|string|null $value = null,
    ) {
    }
}
