<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SupplyRequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SupplyRequestStatus
 *
 * @description Code type wrapper for SupplyRequestStatus enum
 */
class SupplyRequestStatusType extends CodePrimitive
{
    public function __construct(
        /** @param SupplyRequestStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
