<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductStorageScale;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductStorageScale
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductStorageScale enum
 */
class FHIRFHIRBiologicallyDerivedProductStorageScaleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBiologicallyDerivedProductStorageScale|string|null $value The code value */
        public FHIRFHIRBiologicallyDerivedProductStorageScale|string|null $value = null,
    ) {
    }
}
