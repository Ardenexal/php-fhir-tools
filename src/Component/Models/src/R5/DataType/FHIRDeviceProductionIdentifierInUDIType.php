<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceProductionIdentifierInUDI;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceProductionIdentifierInUDI
 *
 * @description Code type wrapper for FHIRDeviceProductionIdentifierInUDI enum
 */
class FHIRDeviceProductionIdentifierInUDIType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceProductionIdentifierInUDI|string|null $value The code value */
        public FHIRDeviceProductionIdentifierInUDI|string|null $value = null,
    ) {
    }
}
