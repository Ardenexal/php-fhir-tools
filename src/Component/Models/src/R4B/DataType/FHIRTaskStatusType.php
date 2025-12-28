<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTaskStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTaskStatus
 *
 * @description Code type wrapper for FHIRTaskStatus enum
 */
class FHIRTaskStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTaskStatus|string|null $value The code value */
        public FHIRTaskStatus|string|null $value = null,
    ) {
    }
}
