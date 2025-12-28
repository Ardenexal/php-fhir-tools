<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCodeSearchSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSearchSupport
 *
 * @description Code type wrapper for FHIRCodeSearchSupport enum
 */
class FHIRFHIRCodeSearchSupportType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCodeSearchSupport|string|null $value The code value */
        public FHIRFHIRCodeSearchSupport|string|null $value = null,
    ) {
    }
}
