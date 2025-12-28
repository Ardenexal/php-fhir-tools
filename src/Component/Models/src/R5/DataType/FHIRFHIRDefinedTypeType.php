<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDefinedType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRDefinedType
 *
 * @description Code type wrapper for FHIRFHIRDefinedType enum
 */
class FHIRFHIRDefinedTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDefinedType|string|null $value The code value */
        public FHIRFHIRDefinedType|string|null $value = null,
    ) {
    }
}
