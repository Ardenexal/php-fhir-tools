<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductStatus
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductStatus enum
 */
class FHIRFHIRBiologicallyDerivedProductStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBiologicallyDerivedProductStatus|string|null $value The code value */
        public FHIRFHIRBiologicallyDerivedProductStatus|string|null $value = null,
    ) {
    }
}
