<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIROrientationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROrientationType
 *
 * @description Code type wrapper for FHIROrientationType enum
 */
class FHIRFHIROrientationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIROrientationType|string|null $value The code value */
        public FHIRFHIROrientationType|string|null $value = null,
    ) {
    }
}
