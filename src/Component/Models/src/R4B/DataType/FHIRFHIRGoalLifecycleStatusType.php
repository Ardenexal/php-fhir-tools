<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGoalLifecycleStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGoalLifecycleStatus
 *
 * @description Code type wrapper for FHIRGoalLifecycleStatus enum
 */
class FHIRFHIRGoalLifecycleStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGoalLifecycleStatus|string|null $value The code value */
        public FHIRFHIRGoalLifecycleStatus|string|null $value = null,
    ) {
    }
}
