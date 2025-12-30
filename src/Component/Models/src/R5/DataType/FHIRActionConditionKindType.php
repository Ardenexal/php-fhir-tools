<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionConditionKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionConditionKind
 *
 * @description Code type wrapper for FHIRActionConditionKind enum
 */
class FHIRActionConditionKindType extends FHIRCode
{
    public function __construct(
        /** @param FHIRActionConditionKind|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
