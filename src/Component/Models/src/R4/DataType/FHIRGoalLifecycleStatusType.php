<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGoalLifecycleStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGoalLifecycleStatus
 *
 * @description Code type wrapper for FHIRGoalLifecycleStatus enum
 */
class FHIRGoalLifecycleStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGoalLifecycleStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
