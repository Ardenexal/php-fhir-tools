<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanIntent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCarePlanIntent
 *
 * @description Code type wrapper for FHIRCarePlanIntent enum
 */
class FHIRFHIRCarePlanIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCarePlanIntent|string|null $value The code value */
        public FHIRFHIRCarePlanIntent|string|null $value = null,
    ) {
    }
}
