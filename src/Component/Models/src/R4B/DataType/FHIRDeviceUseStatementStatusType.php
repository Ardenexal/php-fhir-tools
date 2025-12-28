<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceUseStatementStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceUseStatementStatus
 *
 * @description Code type wrapper for FHIRDeviceUseStatementStatus enum
 */
class FHIRDeviceUseStatementStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceUseStatementStatus|string|null $value The code value */
        public FHIRDeviceUseStatementStatus|string|null $value = null,
    ) {
    }
}
