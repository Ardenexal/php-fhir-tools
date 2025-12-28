<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIROrientationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROrientationType
 *
 * @description Code type wrapper for FHIROrientationType enum
 */
class FHIROrientationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIROrientationType|string|null $value The code value */
        public FHIROrientationType|string|null $value = null,
    ) {
    }
}
