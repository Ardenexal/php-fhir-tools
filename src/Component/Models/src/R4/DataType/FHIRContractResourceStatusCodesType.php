<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContractResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContractResourceStatusCodes
 *
 * @description Code type wrapper for FHIRContractResourceStatusCodes enum
 */
class FHIRContractResourceStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRContractResourceStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
