<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTaskStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTaskStatus
 *
 * @description Code type wrapper for FHIRTaskStatus enum
 */
class FHIRFHIRTaskStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTaskStatus|string|null $value The code value */
        public FHIRFHIRTaskStatus|string|null $value = null,
    ) {
    }
}
