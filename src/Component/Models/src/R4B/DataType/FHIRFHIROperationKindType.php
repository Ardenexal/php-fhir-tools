<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIROperationKind;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIROperationKind
 *
 * @description Code type wrapper for FHIROperationKind enum
 */
class FHIRFHIROperationKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIROperationKind|string|null $value The code value */
        public FHIRFHIROperationKind|string|null $value = null,
    ) {
    }
}
