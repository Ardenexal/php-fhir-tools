<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGraphCompartmentUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGraphCompartmentUse
 *
 * @description Code type wrapper for FHIRGraphCompartmentUse enum
 */
class FHIRGraphCompartmentUseType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGraphCompartmentUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
