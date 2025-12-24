<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRequestResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRRequestResourceType
 *
 * @description Code type wrapper for FHIRRequestResourceType enum
 */
class FHIRFHIRRequestResourceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRequestResourceType|string|null $value The code value */
        public FHIRFHIRRequestResourceType|string|null $value = null,
    ) {
    }
}
