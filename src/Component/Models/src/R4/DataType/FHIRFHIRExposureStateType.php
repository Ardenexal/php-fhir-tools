<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExposureState;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExposureState
 *
 * @description Code type wrapper for FHIRExposureState enum
 */
class FHIRFHIRExposureStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRExposureState|string|null $value The code value */
        public FHIRFHIRExposureState|string|null $value = null,
    ) {
    }
}
