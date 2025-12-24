<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGroupMeasure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGroupMeasure
 *
 * @description Code type wrapper for FHIRGroupMeasure enum
 */
class FHIRFHIRGroupMeasureType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGroupMeasure|string|null $value The code value */
        public FHIRFHIRGroupMeasure|string|null $value = null,
    ) {
    }
}
