<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ContractResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ContractResourceStatusCodes
 *
 * @description Code type wrapper for ContractResourceStatusCodes enum
 */
class ContractResourceStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param ContractResourceStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
