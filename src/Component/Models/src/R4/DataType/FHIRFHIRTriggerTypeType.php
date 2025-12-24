<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTriggerType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTriggerType
 *
 * @description Code type wrapper for FHIRTriggerType enum
 */
class FHIRFHIRTriggerTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTriggerType|string|null $value The code value */
        public FHIRFHIRTriggerType|string|null $value = null,
    ) {
    }
}
