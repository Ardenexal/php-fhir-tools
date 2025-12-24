<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImmunizationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRImmunizationStatusCodes
 *
 * @description Code type wrapper for FHIRImmunizationStatusCodes enum
 */
class FHIRFHIRImmunizationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImmunizationStatusCodes|string|null $value The code value */
        public FHIRFHIRImmunizationStatusCodes|string|null $value = null,
    ) {
    }
}
