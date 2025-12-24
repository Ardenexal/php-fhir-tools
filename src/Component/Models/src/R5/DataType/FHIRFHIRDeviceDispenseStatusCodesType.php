<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceDispenseStatusCodes
 *
 * @description Code type wrapper for FHIRDeviceDispenseStatusCodes enum
 */
class FHIRFHIRDeviceDispenseStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceDispenseStatusCodes|string|null $value The code value */
        public FHIRFHIRDeviceDispenseStatusCodes|string|null $value = null,
    ) {
    }
}
