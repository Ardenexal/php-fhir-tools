<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFormularyItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFormularyItemStatusCodes
 *
 * @description Code type wrapper for FHIRFormularyItemStatusCodes enum
 */
class FHIRFormularyItemStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFormularyItemStatusCodes|string|null $value The code value */
        public FHIRFormularyItemStatusCodes|string|null $value = null,
    ) {
    }
}
