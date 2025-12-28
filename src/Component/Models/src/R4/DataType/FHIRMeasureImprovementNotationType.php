<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMeasureImprovementNotation;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureImprovementNotation
 *
 * @description Code type wrapper for FHIRMeasureImprovementNotation enum
 */
class FHIRMeasureImprovementNotationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMeasureImprovementNotation|string|null $value The code value */
        public FHIRMeasureImprovementNotation|string|null $value = null,
    ) {
    }
}
