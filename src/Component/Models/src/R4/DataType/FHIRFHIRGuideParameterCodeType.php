<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuideParameterCode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGuideParameterCode
 *
 * @description Code type wrapper for FHIRGuideParameterCode enum
 */
class FHIRFHIRGuideParameterCodeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGuideParameterCode|string|null $value The code value */
        public FHIRFHIRGuideParameterCode|string|null $value = null,
    ) {
    }
}
