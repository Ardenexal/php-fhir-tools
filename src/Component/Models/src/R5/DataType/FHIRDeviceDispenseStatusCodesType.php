<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceDispenseStatusCodes
 *
 * @description Code type wrapper for FHIRDeviceDispenseStatusCodes enum
 */
class FHIRDeviceDispenseStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceDispenseStatusCodes|string|null $value The code value */
        public FHIRDeviceDispenseStatusCodes|string|null $value = null,
    ) {
    }
}
