<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRKind;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRKind
 *
 * @description Code type wrapper for FHIRKind enum
 */
class FHIRKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRKind|string|null $value The code value */
        public FHIRKind|string|null $value = null,
    ) {
    }
}
