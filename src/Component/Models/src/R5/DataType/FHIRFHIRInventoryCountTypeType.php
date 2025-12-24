<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryCountType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryCountType
 *
 * @description Code type wrapper for FHIRInventoryCountType enum
 */
class FHIRFHIRInventoryCountTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInventoryCountType|string|null $value The code value */
        public FHIRFHIRInventoryCountType|string|null $value = null,
    ) {
    }
}
