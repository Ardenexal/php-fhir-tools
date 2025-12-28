<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRAllTypes
 *
 * @description Code type wrapper for FHIRFHIRAllTypes enum
 */
class FHIRFHIRAllTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllTypes|string|null $value The code value */
        public FHIRFHIRAllTypes|string|null $value = null,
    ) {
    }
}
