<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\InventoryItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type InventoryItemStatusCodes
 *
 * @description Code type wrapper for InventoryItemStatusCodes enum
 */
class InventoryItemStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param InventoryItemStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
