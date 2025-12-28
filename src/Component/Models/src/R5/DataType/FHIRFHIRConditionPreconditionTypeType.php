<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionPreconditionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionPreconditionType
 *
 * @description Code type wrapper for FHIRConditionPreconditionType enum
 */
class FHIRFHIRConditionPreconditionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConditionPreconditionType|string|null $value The code value */
        public FHIRFHIRConditionPreconditionType|string|null $value = null,
    ) {
    }
}
