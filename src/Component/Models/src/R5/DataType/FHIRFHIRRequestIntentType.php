<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRequestIntent;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRequestIntent
 *
 * @description Code type wrapper for FHIRRequestIntent enum
 */
class FHIRFHIRRequestIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRequestIntent|string|null $value The code value */
        public FHIRFHIRRequestIntent|string|null $value = null,
    ) {
    }
}
