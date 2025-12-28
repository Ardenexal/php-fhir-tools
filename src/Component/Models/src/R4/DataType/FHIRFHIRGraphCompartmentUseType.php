<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGraphCompartmentUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGraphCompartmentUse
 *
 * @description Code type wrapper for FHIRGraphCompartmentUse enum
 */
class FHIRFHIRGraphCompartmentUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGraphCompartmentUse|string|null $value The code value */
        public FHIRFHIRGraphCompartmentUse|string|null $value = null,
    ) {
    }
}
