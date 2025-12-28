<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuidePageGeneration;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGuidePageGeneration
 *
 * @description Code type wrapper for FHIRGuidePageGeneration enum
 */
class FHIRFHIRGuidePageGenerationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGuidePageGeneration|string|null $value The code value */
        public FHIRFHIRGuidePageGeneration|string|null $value = null,
    ) {
    }
}
