<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTypeDerivationRule;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTypeDerivationRule
 *
 * @description Code type wrapper for FHIRTypeDerivationRule enum
 */
class FHIRFHIRTypeDerivationRuleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTypeDerivationRule|string|null $value The code value */
        public FHIRFHIRTypeDerivationRule|string|null $value = null,
    ) {
    }
}
