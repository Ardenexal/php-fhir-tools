<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceProductionIdentifierInUDI;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceProductionIdentifierInUDI
 *
 * @description Code type wrapper for FHIRDeviceProductionIdentifierInUDI enum
 */
class FHIRFHIRDeviceProductionIdentifierInUDIType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceProductionIdentifierInUDI|string|null $value The code value */
        public FHIRFHIRDeviceProductionIdentifierInUDI|string|null $value = null,
    ) {
    }
}
