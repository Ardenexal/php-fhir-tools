<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRUCUMCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRUCUMCodes
 *
 * @description Code type wrapper for FHIRUCUMCodes enum
 */
class FHIRFHIRUCUMCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRUCUMCodes|string|null $value The code value */
        public FHIRFHIRUCUMCodes|string|null $value = null,
    ) {
    }
}
