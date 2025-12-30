<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRTypeDerivationRule|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
