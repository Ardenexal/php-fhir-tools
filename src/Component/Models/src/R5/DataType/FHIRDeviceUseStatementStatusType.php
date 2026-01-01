<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRDeviceUseStatementStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
