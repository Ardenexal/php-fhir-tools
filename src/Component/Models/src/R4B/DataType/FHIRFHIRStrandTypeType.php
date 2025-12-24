<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStrandType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStrandType
 *
 * @description Code type wrapper for FHIRStrandType enum
 */
class FHIRFHIRStrandTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStrandType|string|null $value The code value */
        public FHIRFHIRStrandType|string|null $value = null,
    ) {
    }
}
