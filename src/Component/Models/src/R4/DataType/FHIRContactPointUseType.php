<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContactPointUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContactPointUse
 *
 * @description Code type wrapper for FHIRContactPointUse enum
 */
class FHIRContactPointUseType extends FHIRCode
{
    public function __construct(
        /** @param FHIRContactPointUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
