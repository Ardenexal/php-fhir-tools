<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRVisionBase;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRVisionBase
 *
 * @description Code type wrapper for FHIRVisionBase enum
 */
class FHIRFHIRVisionBaseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRVisionBase|string|null $value The code value */
        public FHIRFHIRVisionBase|string|null $value = null,
    ) {
    }
}
