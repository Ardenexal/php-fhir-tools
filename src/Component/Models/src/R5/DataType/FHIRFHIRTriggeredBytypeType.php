<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRTriggeredBytype;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTriggeredBytype
 *
 * @description Code type wrapper for FHIRTriggeredBytype enum
 */
class FHIRFHIRTriggeredBytypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTriggeredBytype|string|null $value The code value */
        public FHIRFHIRTriggeredBytype|string|null $value = null,
    ) {
    }
}
