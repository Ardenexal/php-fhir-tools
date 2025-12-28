<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTriggeredBytype;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTriggeredBytype
 *
 * @description Code type wrapper for FHIRTriggeredBytype enum
 */
class FHIRTriggeredBytypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTriggeredBytype|string|null $value The code value */
        public FHIRTriggeredBytype|string|null $value = null,
    ) {
    }
}
