<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRLocationStatus
 *
 * @description Code type wrapper for FHIRLocationStatus enum
 */
class FHIRFHIRLocationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRLocationStatus|string|null $value The code value */
        public FHIRFHIRLocationStatus|string|null $value = null,
    ) {
    }
}
