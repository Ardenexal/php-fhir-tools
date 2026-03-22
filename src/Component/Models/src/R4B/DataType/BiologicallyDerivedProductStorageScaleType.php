<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\BiologicallyDerivedProductStorageScale;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
