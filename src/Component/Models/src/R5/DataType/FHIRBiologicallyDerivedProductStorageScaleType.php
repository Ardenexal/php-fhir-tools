<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBiologicallyDerivedProductStorageScale;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductStorageScale
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductStorageScale enum
 */
class FHIRBiologicallyDerivedProductStorageScaleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRBiologicallyDerivedProductStorageScale|string|null $value The code value */
        public FHIRBiologicallyDerivedProductStorageScale|string|null $value = null,
    ) {
    }
}
