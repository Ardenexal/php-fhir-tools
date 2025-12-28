<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResponseType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResponseType
 *
 * @description Code type wrapper for FHIRResponseType enum
 */
class FHIRResponseTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResponseType|string|null $value The code value */
        public FHIRResponseType|string|null $value = null,
    ) {
    }
}
