<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResponseType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResponseType
 *
 * @description Code type wrapper for FHIRResponseType enum
 */
class FHIRFHIRResponseTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResponseType|string|null $value The code value */
        public FHIRFHIRResponseType|string|null $value = null,
    ) {
    }
}
