<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIROperationKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIROperationKind
 *
 * @description Code type wrapper for FHIROperationKind enum
 */
class FHIROperationKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIROperationKind|string|null $value The code value */
        public FHIROperationKind|string|null $value = null,
    ) {
    }
}
