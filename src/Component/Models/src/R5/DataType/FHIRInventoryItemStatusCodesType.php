<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryItemStatusCodes
 *
 * @description Code type wrapper for FHIRInventoryItemStatusCodes enum
 */
class FHIRInventoryItemStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInventoryItemStatusCodes|string|null $value The code value */
        public FHIRInventoryItemStatusCodes|string|null $value = null,
    ) {
    }
}
