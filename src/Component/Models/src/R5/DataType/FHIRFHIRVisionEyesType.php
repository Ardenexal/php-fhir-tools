<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRVisionEyes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVisionEyes
 *
 * @description Code type wrapper for FHIRVisionEyes enum
 */
class FHIRFHIRVisionEyesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRVisionEyes|string|null $value The code value */
        public FHIRFHIRVisionEyes|string|null $value = null,
    ) {
    }
}
