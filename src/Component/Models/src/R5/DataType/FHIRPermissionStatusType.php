<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPermissionStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPermissionStatus
 *
 * @description Code type wrapper for FHIRPermissionStatus enum
 */
class FHIRPermissionStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRPermissionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
