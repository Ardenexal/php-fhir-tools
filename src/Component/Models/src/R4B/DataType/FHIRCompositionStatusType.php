<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCompositionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompositionStatus
 *
 * @description Code type wrapper for FHIRCompositionStatus enum
 */
class FHIRCompositionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCompositionStatus|string|null $value The code value */
        public FHIRCompositionStatus|string|null $value = null,
    ) {
    }
}
