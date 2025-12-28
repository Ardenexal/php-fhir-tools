<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMessageheaderResponseRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMessageheaderResponseRequest
 *
 * @description Code type wrapper for FHIRMessageheaderResponseRequest enum
 */
class FHIRFHIRMessageheaderResponseRequestType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMessageheaderResponseRequest|string|null $value The code value */
        public FHIRFHIRMessageheaderResponseRequest|string|null $value = null,
    ) {
    }
}
