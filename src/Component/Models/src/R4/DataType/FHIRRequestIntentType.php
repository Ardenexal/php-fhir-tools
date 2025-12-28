<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRequestIntent;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRequestIntent
 *
 * @description Code type wrapper for FHIRRequestIntent enum
 */
class FHIRRequestIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRRequestIntent|string|null $value The code value */
        public FHIRRequestIntent|string|null $value = null,
    ) {
    }
}
