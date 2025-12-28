<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCarePlanActivityStatus
 *
 * @description Code type wrapper for FHIRCarePlanActivityStatus enum
 */
class FHIRFHIRCarePlanActivityStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCarePlanActivityStatus|string|null $value The code value */
        public FHIRFHIRCarePlanActivityStatus|string|null $value = null,
    ) {
    }
}
