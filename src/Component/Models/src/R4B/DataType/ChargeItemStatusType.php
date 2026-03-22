<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ChargeItemStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ChargeItemStatus
 *
 * @description Code type wrapper for ChargeItemStatus enum
 */
class ChargeItemStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ChargeItemStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
