<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRXPathUsageType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRXPathUsageType
 *
 * @description Code type wrapper for FHIRXPathUsageType enum
 */
class FHIRXPathUsageTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRXPathUsageType|string|null $value The code value */
        public FHIRXPathUsageType|string|null $value = null,
    ) {
    }
}
