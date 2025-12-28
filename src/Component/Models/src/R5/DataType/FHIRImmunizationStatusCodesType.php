<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImmunizationStatusCodes
 *
 * @description Code type wrapper for FHIRImmunizationStatusCodes enum
 */
class FHIRImmunizationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRImmunizationStatusCodes|string|null $value The code value */
        public FHIRImmunizationStatusCodes|string|null $value = null,
    ) {
    }
}
