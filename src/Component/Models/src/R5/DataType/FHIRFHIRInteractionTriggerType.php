<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRInteractionTrigger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInteractionTrigger
 *
 * @description Code type wrapper for FHIRInteractionTrigger enum
 */
class FHIRFHIRInteractionTriggerType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInteractionTrigger|string|null $value The code value */
        public FHIRFHIRInteractionTrigger|string|null $value = null,
    ) {
    }
}
