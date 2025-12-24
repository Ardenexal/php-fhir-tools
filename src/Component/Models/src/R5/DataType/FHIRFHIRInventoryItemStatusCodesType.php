<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryItemStatusCodes
 *
 * @description Code type wrapper for FHIRInventoryItemStatusCodes enum
 */
class FHIRFHIRInventoryItemStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInventoryItemStatusCodes|string|null $value The code value */
        public FHIRFHIRInventoryItemStatusCodes|string|null $value = null,
    ) {
    }
}
