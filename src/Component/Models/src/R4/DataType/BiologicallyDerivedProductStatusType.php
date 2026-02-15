<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\BiologicallyDerivedProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type BiologicallyDerivedProductStatus
 *
 * @description Code type wrapper for BiologicallyDerivedProductStatus enum
 */
class BiologicallyDerivedProductStatusType extends CodePrimitive
{
    public function __construct(
        /** @param BiologicallyDerivedProductStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
