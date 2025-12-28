<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSequenceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSequenceType
 *
 * @description Code type wrapper for FHIRSequenceType enum
 */
class FHIRSequenceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSequenceType|string|null $value The code value */
        public FHIRSequenceType|string|null $value = null,
    ) {
    }
}
