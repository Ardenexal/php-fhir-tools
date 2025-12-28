<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRVersion;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRVersion
 *
 * @description Code type wrapper for FHIRFHIRVersion enum
 */
class FHIRFHIRVersionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRVersion|string|null $value The code value */
        public FHIRFHIRVersion|string|null $value = null,
    ) {
    }
}
