<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionalReadStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionalReadStatus
 *
 * @description Code type wrapper for FHIRConditionalReadStatus enum
 */
class FHIRFHIRConditionalReadStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConditionalReadStatus|string|null $value The code value */
        public FHIRFHIRConditionalReadStatus|string|null $value = null,
    ) {
    }
}
