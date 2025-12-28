<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBundleType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBundleType
 *
 * @description Code type wrapper for FHIRBundleType enum
 */
class FHIRFHIRBundleTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBundleType|string|null $value The code value */
        public FHIRFHIRBundleType|string|null $value = null,
    ) {
    }
}
