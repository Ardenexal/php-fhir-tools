<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSequenceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSequenceType
 *
 * @description Code type wrapper for FHIRSequenceType enum
 */
class FHIRFHIRSequenceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSequenceType|string|null $value The code value */
        public FHIRFHIRSequenceType|string|null $value = null,
    ) {
    }
}
