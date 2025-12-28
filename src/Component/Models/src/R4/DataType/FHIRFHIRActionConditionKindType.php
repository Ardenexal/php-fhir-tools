<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionConditionKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionConditionKind
 *
 * @description Code type wrapper for FHIRActionConditionKind enum
 */
class FHIRFHIRActionConditionKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionConditionKind|string|null $value The code value */
        public FHIRFHIRActionConditionKind|string|null $value = null,
    ) {
    }
}
