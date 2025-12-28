<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLocationStatus
 *
 * @description Code type wrapper for FHIRLocationStatus enum
 */
class FHIRLocationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRLocationStatus|string|null $value The code value */
        public FHIRLocationStatus|string|null $value = null,
    ) {
    }
}
