<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFinancialResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRFinancialResourceStatusCodes
 *
 * @description Code type wrapper for FHIRFinancialResourceStatusCodes enum
 */
class FHIRFHIRFinancialResourceStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFinancialResourceStatusCodes|string|null $value The code value */
        public FHIRFHIRFinancialResourceStatusCodes|string|null $value = null,
    ) {
    }
}
