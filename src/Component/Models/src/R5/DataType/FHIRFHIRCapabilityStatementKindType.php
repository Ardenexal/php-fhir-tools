<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCapabilityStatementKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCapabilityStatementKind
 *
 * @description Code type wrapper for FHIRCapabilityStatementKind enum
 */
class FHIRFHIRCapabilityStatementKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCapabilityStatementKind|string|null $value The code value */
        public FHIRFHIRCapabilityStatementKind|string|null $value = null,
    ) {
    }
}
