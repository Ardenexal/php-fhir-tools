<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRUCUMCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRUCUMCodes
 *
 * @description Code type wrapper for FHIRUCUMCodes enum
 */
class FHIRUCUMCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRUCUMCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
