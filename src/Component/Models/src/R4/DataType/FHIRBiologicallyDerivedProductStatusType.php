<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBiologicallyDerivedProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductStatus
 *
 * @description Code type wrapper for FHIRBiologicallyDerivedProductStatus enum
 */
class FHIRBiologicallyDerivedProductStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRBiologicallyDerivedProductStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
