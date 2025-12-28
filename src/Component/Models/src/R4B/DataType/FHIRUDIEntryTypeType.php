<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRUDIEntryType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUDIEntryType
 *
 * @description Code type wrapper for FHIRUDIEntryType enum
 */
class FHIRUDIEntryTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRUDIEntryType|string|null $value The code value */
        public FHIRUDIEntryType|string|null $value = null,
    ) {
    }
}
