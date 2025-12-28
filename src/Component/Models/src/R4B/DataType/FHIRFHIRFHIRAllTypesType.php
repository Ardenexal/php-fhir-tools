<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRAllTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRAllTypes
 *
 * @description Code type wrapper for FHIRFHIRAllTypes enum
 */
class FHIRFHIRFHIRAllTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRAllTypes|string|null $value The code value */
        public FHIRFHIRFHIRAllTypes|string|null $value = null,
    ) {
    }
}
