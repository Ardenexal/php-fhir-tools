<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeDerivationRule;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTypeDerivationRule
 *
 * @description Code type wrapper for FHIRTypeDerivationRule enum
 */
class FHIRTypeDerivationRuleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTypeDerivationRule|string|null $value The code value */
        public FHIRTypeDerivationRule|string|null $value = null,
    ) {
    }
}
