<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRInteractionTrigger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInteractionTrigger
 *
 * @description Code type wrapper for FHIRInteractionTrigger enum
 */
class FHIRInteractionTriggerType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInteractionTrigger|string|null $value The code value */
        public FHIRInteractionTrigger|string|null $value = null,
    ) {
    }
}
