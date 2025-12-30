<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRIdentifierUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIdentifierUse
 *
 * @description Code type wrapper for FHIRIdentifierUse enum
 */
class FHIRIdentifierUseType extends FHIRCode
{
    public function __construct(
        /** @param FHIRIdentifierUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
