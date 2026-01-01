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
        /** @param FHIRFHIRDefinedType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
