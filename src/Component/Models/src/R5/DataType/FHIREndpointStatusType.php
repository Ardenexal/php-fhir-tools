<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREndpointStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREndpointStatus
 *
 * @description Code type wrapper for FHIREndpointStatus enum
 */
class FHIREndpointStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIREndpointStatus|string|null $value The code value */
        public FHIREndpointStatus|string|null $value = null,
    ) {
    }
}
