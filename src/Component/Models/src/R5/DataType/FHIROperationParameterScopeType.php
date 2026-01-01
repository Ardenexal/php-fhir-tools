<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIROperationParameterScope;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROperationParameterScope
 *
 * @description Code type wrapper for FHIROperationParameterScope enum
 */
class FHIROperationParameterScopeType extends FHIRCode
{
    public function __construct(
        /** @param FHIROperationParameterScope|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
