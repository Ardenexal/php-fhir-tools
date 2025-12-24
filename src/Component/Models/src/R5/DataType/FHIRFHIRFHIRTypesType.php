<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRFHIRTypes;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRTypes
 *
 * @description Code type wrapper for FHIRFHIRTypes enum
 */
class FHIRFHIRFHIRTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRTypes|string|null $value The code value */
        public FHIRFHIRFHIRTypes|string|null $value = null,
    ) {
    }
}
