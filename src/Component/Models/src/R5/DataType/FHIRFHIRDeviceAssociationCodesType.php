<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceAssociationCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceAssociationCodes
 *
 * @description Code type wrapper for FHIRDeviceAssociationCodes enum
 */
class FHIRFHIRDeviceAssociationCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceAssociationCodes|string|null $value The code value */
        public FHIRFHIRDeviceAssociationCodes|string|null $value = null,
    ) {
    }
}
