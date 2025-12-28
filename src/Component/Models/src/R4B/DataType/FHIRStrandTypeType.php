<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStrandType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStrandType
 *
 * @description Code type wrapper for FHIRStrandType enum
 */
class FHIRStrandTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStrandType|string|null $value The code value */
        public FHIRStrandType|string|null $value = null,
    ) {
    }
}
