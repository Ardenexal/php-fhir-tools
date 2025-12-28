<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCarePlanActivityKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCarePlanActivityKind
 *
 * @description Code type wrapper for FHIRCarePlanActivityKind enum
 */
class FHIRCarePlanActivityKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCarePlanActivityKind|string|null $value The code value */
        public FHIRCarePlanActivityKind|string|null $value = null,
    ) {
    }
}
