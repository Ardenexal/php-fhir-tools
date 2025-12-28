<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRKind;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRKind
 *
 * @description Code type wrapper for FHIRKind enum
 */
class FHIRFHIRKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRKind|string|null $value The code value */
        public FHIRFHIRKind|string|null $value = null,
    ) {
    }
}
