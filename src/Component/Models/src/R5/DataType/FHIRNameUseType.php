<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRNameUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNameUse
 *
 * @description Code type wrapper for FHIRNameUse enum
 */
class FHIRNameUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRNameUse|string|null $value The code value */
        public FHIRNameUse|string|null $value = null,
    ) {
    }
}
