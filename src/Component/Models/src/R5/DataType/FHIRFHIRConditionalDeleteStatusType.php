<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionalDeleteStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRConditionalDeleteStatus
 *
 * @description Code type wrapper for FHIRConditionalDeleteStatus enum
 */
class FHIRFHIRConditionalDeleteStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConditionalDeleteStatus|string|null $value The code value */
        public FHIRFHIRConditionalDeleteStatus|string|null $value = null,
    ) {
    }
}
