<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREndpointStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREndpointStatus
 *
 * @description Code type wrapper for FHIREndpointStatus enum
 */
class FHIRFHIREndpointStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREndpointStatus|string|null $value The code value */
        public FHIRFHIREndpointStatus|string|null $value = null,
    ) {
    }
}
