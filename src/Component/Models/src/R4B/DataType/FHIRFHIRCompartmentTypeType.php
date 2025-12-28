<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCompartmentType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompartmentType
 *
 * @description Code type wrapper for FHIRCompartmentType enum
 */
class FHIRFHIRCompartmentTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCompartmentType|string|null $value The code value */
        public FHIRFHIRCompartmentType|string|null $value = null,
    ) {
    }
}
