<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionalDeleteStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionalDeleteStatus
 *
 * @description Code type wrapper for FHIRConditionalDeleteStatus enum
 */
class FHIRConditionalDeleteStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConditionalDeleteStatus|string|null $value The code value */
        public FHIRConditionalDeleteStatus|string|null $value = null,
    ) {
    }
}
