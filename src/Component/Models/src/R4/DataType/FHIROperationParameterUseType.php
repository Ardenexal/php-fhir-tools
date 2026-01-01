<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIROperationParameterUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROperationParameterUse
 *
 * @description Code type wrapper for FHIROperationParameterUse enum
 */
class FHIROperationParameterUseType extends FHIRCode
{
    public function __construct(
        /** @param FHIROperationParameterUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
