<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRResourceType
 *
 * @description Code type wrapper for FHIRResourceType enum
 */
class FHIRFHIRResourceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResourceType|string|null $value The code value */
        public FHIRFHIRResourceType|string|null $value = null,
    ) {
    }
}
