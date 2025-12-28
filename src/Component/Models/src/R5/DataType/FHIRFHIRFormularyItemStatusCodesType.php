<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRFormularyItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFormularyItemStatusCodes
 *
 * @description Code type wrapper for FHIRFormularyItemStatusCodes enum
 */
class FHIRFHIRFormularyItemStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFormularyItemStatusCodes|string|null $value The code value */
        public FHIRFHIRFormularyItemStatusCodes|string|null $value = null,
    ) {
    }
}
