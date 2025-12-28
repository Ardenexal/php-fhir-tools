<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSortDirection;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSortDirection
 *
 * @description Code type wrapper for FHIRSortDirection enum
 */
class FHIRSortDirectionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSortDirection|string|null $value The code value */
        public FHIRSortDirection|string|null $value = null,
    ) {
    }
}
