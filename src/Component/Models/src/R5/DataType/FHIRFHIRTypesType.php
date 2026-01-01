<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRTypes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRTypes
 *
 * @description Code type wrapper for FHIRFHIRTypes enum
 */
class FHIRFHIRTypesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRFHIRTypes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
