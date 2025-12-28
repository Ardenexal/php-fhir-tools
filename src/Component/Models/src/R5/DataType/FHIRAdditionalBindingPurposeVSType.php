<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAdditionalBindingPurposeVS;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdditionalBindingPurposeVS
 *
 * @description Code type wrapper for FHIRAdditionalBindingPurposeVS enum
 */
class FHIRAdditionalBindingPurposeVSType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAdditionalBindingPurposeVS|string|null $value The code value */
        public FHIRAdditionalBindingPurposeVS|string|null $value = null,
    ) {
    }
}
