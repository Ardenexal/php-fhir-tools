<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFinancialResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFinancialResourceStatusCodes
 *
 * @description Code type wrapper for FHIRFinancialResourceStatusCodes enum
 */
class FHIRFinancialResourceStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFinancialResourceStatusCodes|string|null $value The code value */
        public FHIRFinancialResourceStatusCodes|string|null $value = null,
    ) {
    }
}
