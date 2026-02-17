<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\BiologicallyDerivedProductStorageScale;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type BiologicallyDerivedProductStorageScale
 *
 * @description Code type wrapper for BiologicallyDerivedProductStorageScale enum
 */
class BiologicallyDerivedProductStorageScaleType extends CodePrimitive
{
    public function __construct(
        /** @param BiologicallyDerivedProductStorageScale|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
