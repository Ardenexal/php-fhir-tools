<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdditionalBindingPurposeVS;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAdditionalBindingPurposeVS
 *
 * @description Code type wrapper for FHIRAdditionalBindingPurposeVS enum
 */
class FHIRFHIRAdditionalBindingPurposeVSType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdditionalBindingPurposeVS|string|null $value The code value */
        public FHIRFHIRAdditionalBindingPurposeVS|string|null $value = null,
    ) {
    }
}
