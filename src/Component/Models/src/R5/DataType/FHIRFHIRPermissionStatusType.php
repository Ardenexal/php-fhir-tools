<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPermissionStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRPermissionStatus
 *
 * @description Code type wrapper for FHIRPermissionStatus enum
 */
class FHIRFHIRPermissionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPermissionStatus|string|null $value The code value */
        public FHIRFHIRPermissionStatus|string|null $value = null,
    ) {
    }
}
