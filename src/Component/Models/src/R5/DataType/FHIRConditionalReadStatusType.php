<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionalReadStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionalReadStatus
 *
 * @description Code type wrapper for FHIRConditionalReadStatus enum
 */
class FHIRConditionalReadStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConditionalReadStatus|string|null $value The code value */
        public FHIRConditionalReadStatus|string|null $value = null,
    ) {
    }
}
