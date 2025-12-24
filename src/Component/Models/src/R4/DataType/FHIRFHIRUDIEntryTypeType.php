<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRUDIEntryType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRUDIEntryType
 *
 * @description Code type wrapper for FHIRUDIEntryType enum
 */
class FHIRFHIRUDIEntryTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRUDIEntryType|string|null $value The code value */
        public FHIRFHIRUDIEntryType|string|null $value = null,
    ) {
    }
}
