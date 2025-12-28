<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBundleType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBundleType
 *
 * @description Code type wrapper for FHIRBundleType enum
 */
class FHIRBundleTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRBundleType|string|null $value The code value */
        public FHIRBundleType|string|null $value = null,
    ) {
    }
}
