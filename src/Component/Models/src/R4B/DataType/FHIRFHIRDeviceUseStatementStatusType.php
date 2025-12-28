<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceUseStatementStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceUseStatementStatus
 *
 * @description Code type wrapper for FHIRDeviceUseStatementStatus enum
 */
class FHIRFHIRDeviceUseStatementStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceUseStatementStatus|string|null $value The code value */
        public FHIRFHIRDeviceUseStatementStatus|string|null $value = null,
    ) {
    }
}
