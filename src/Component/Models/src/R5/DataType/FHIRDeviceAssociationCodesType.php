<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceAssociationCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceAssociationCodes
 *
 * @description Code type wrapper for FHIRDeviceAssociationCodes enum
 */
class FHIRDeviceAssociationCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceAssociationCodes|string|null $value The code value */
        public FHIRDeviceAssociationCodes|string|null $value = null,
    ) {
    }
}
