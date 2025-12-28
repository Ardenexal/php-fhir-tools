<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGuidePageGeneration;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGuidePageGeneration
 *
 * @description Code type wrapper for FHIRGuidePageGeneration enum
 */
class FHIRGuidePageGenerationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRGuidePageGeneration|string|null $value The code value */
        public FHIRGuidePageGeneration|string|null $value = null,
    ) {
    }
}
