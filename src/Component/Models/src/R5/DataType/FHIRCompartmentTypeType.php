<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCompartmentType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompartmentType
 *
 * @description Code type wrapper for FHIRCompartmentType enum
 */
class FHIRCompartmentTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCompartmentType|string|null $value The code value */
        public FHIRCompartmentType|string|null $value = null,
    ) {
    }
}
