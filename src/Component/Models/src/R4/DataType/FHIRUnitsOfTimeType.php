<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRUnitsOfTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUnitsOfTime
 *
 * @description Code type wrapper for FHIRUnitsOfTime enum
 */
class FHIRUnitsOfTimeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRUnitsOfTime|string|null $value The code value */
        public FHIRUnitsOfTime|string|null $value = null,
    ) {
    }
}
