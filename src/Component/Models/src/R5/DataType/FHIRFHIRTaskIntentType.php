<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTaskIntent;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTaskIntent
 *
 * @description Code type wrapper for FHIRTaskIntent enum
 */
class FHIRFHIRTaskIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTaskIntent|string|null $value The code value */
        public FHIRFHIRTaskIntent|string|null $value = null,
    ) {
    }
}
