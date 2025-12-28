<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRDefinedType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRDefinedType
 *
 * @description Code type wrapper for FHIRFHIRDefinedType enum
 */
class FHIRFHIRFHIRDefinedTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRDefinedType|string|null $value The code value */
        public FHIRFHIRFHIRDefinedType|string|null $value = null,
    ) {
    }
}
