<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCarePlanActivityKind
 *
 * @description Code type wrapper for FHIRCarePlanActivityKind enum
 */
class FHIRFHIRCarePlanActivityKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCarePlanActivityKind|string|null $value The code value */
        public FHIRFHIRCarePlanActivityKind|string|null $value = null,
    ) {
    }
}
