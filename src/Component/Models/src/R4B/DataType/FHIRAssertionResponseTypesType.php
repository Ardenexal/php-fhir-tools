<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionResponseTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionResponseTypes
 *
 * @description Code type wrapper for FHIRAssertionResponseTypes enum
 */
class FHIRAssertionResponseTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAssertionResponseTypes|string|null $value The code value */
        public FHIRAssertionResponseTypes|string|null $value = null,
    ) {
    }
}
