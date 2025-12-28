<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRVersion;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRVersion
 *
 * @description Code type wrapper for FHIRFHIRVersion enum
 */
class FHIRFHIRFHIRVersionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRVersion|string|null $value The code value */
        public FHIRFHIRFHIRVersion|string|null $value = null,
    ) {
    }
}
