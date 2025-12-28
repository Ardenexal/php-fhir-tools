<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCarePlanActivityStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCarePlanActivityStatus
 *
 * @description Code type wrapper for FHIRCarePlanActivityStatus enum
 */
class FHIRCarePlanActivityStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCarePlanActivityStatus|string|null $value The code value */
        public FHIRCarePlanActivityStatus|string|null $value = null,
    ) {
    }
}
