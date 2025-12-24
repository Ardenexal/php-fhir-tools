<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRPropertyType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRPropertyType
 *
 * @description Code type wrapper for FHIRPropertyType enum
 */
class FHIRFHIRPropertyTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPropertyType|string|null $value The code value */
        public FHIRFHIRPropertyType|string|null $value = null,
    ) {
    }
}
