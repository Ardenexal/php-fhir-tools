<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCapabilityStatementKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCapabilityStatementKind
 *
 * @description Code type wrapper for FHIRCapabilityStatementKind enum
 */
class FHIRCapabilityStatementKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCapabilityStatementKind|string|null $value The code value */
        public FHIRCapabilityStatementKind|string|null $value = null,
    ) {
    }
}
