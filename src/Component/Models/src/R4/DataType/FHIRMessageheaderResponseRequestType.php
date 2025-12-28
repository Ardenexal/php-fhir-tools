<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMessageheaderResponseRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMessageheaderResponseRequest
 *
 * @description Code type wrapper for FHIRMessageheaderResponseRequest enum
 */
class FHIRMessageheaderResponseRequestType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMessageheaderResponseRequest|string|null $value The code value */
        public FHIRMessageheaderResponseRequest|string|null $value = null,
    ) {
    }
}
