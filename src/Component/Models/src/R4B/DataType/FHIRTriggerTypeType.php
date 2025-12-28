<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTriggerType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTriggerType
 *
 * @description Code type wrapper for FHIRTriggerType enum
 */
class FHIRTriggerTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTriggerType|string|null $value The code value */
        public FHIRTriggerType|string|null $value = null,
    ) {
    }
}
