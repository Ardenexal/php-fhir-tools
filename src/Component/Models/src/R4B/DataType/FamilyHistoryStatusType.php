<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FamilyHistoryStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type FamilyHistoryStatus
 *
 * @description Code type wrapper for FamilyHistoryStatus enum
 */
class FamilyHistoryStatusType extends CodePrimitive
{
    public function __construct(
        /** @param FamilyHistoryStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
