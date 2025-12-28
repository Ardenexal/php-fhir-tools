<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureImprovementNotation;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureImprovementNotation
 *
 * @description Code type wrapper for FHIRMeasureImprovementNotation enum
 */
class FHIRFHIRMeasureImprovementNotationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMeasureImprovementNotation|string|null $value The code value */
        public FHIRFHIRMeasureImprovementNotation|string|null $value = null,
    ) {
    }
}
