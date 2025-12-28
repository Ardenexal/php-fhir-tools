<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRUnitsOfTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUnitsOfTime
 *
 * @description Code type wrapper for FHIRUnitsOfTime enum
 */
class FHIRFHIRUnitsOfTimeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRUnitsOfTime|string|null $value The code value */
        public FHIRFHIRUnitsOfTime|string|null $value = null,
    ) {
    }
}
