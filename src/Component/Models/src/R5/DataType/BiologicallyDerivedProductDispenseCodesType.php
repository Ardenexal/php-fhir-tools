<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\BiologicallyDerivedProductDispenseCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type BiologicallyDerivedProductDispenseCodes
 *
 * @description Code type wrapper for BiologicallyDerivedProductDispenseCodes enum
 */
class BiologicallyDerivedProductDispenseCodesType extends CodePrimitive
{
    public function __construct(
        /** @param BiologicallyDerivedProductDispenseCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
