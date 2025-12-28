<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRExposureState;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExposureState
 *
 * @description Code type wrapper for FHIRExposureState enum
 */
class FHIRExposureStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRExposureState|string|null $value The code value */
        public FHIRExposureState|string|null $value = null,
    ) {
    }
}
