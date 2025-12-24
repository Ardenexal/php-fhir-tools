<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRXPathUsageType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRXPathUsageType
 *
 * @description Code type wrapper for FHIRXPathUsageType enum
 */
class FHIRFHIRXPathUsageTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRXPathUsageType|string|null $value The code value */
        public FHIRFHIRXPathUsageType|string|null $value = null,
    ) {
    }
}
