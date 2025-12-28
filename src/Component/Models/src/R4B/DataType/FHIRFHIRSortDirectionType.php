<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSortDirection;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSortDirection
 *
 * @description Code type wrapper for FHIRSortDirection enum
 */
class FHIRFHIRSortDirectionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSortDirection|string|null $value The code value */
        public FHIRFHIRSortDirection|string|null $value = null,
    ) {
    }
}
