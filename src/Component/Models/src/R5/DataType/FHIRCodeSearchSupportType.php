<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSearchSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSearchSupport
 *
 * @description Code type wrapper for FHIRCodeSearchSupport enum
 */
class FHIRCodeSearchSupportType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCodeSearchSupport|string|null $value The code value */
        public FHIRCodeSearchSupport|string|null $value = null,
    ) {
    }
}
