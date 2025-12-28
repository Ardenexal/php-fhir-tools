<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRPropertyType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPropertyType
 *
 * @description Code type wrapper for FHIRPropertyType enum
 */
class FHIRPropertyTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRPropertyType|string|null $value The code value */
        public FHIRPropertyType|string|null $value = null,
    ) {
    }
}
