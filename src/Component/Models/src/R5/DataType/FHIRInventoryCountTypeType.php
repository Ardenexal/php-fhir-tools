<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryCountType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryCountType
 *
 * @description Code type wrapper for FHIRInventoryCountType enum
 */
class FHIRInventoryCountTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInventoryCountType|string|null $value The code value */
        public FHIRInventoryCountType|string|null $value = null,
    ) {
    }
}
