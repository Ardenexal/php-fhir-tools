<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRVersionIndependentResourceTypesAll;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVersionIndependentResourceTypesAll
 *
 * @description Code type wrapper for FHIRVersionIndependentResourceTypesAll enum
 */
class FHIRVersionIndependentResourceTypesAllType extends FHIRCode
{
    public function __construct(
        /** @param FHIRVersionIndependentResourceTypesAll|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
