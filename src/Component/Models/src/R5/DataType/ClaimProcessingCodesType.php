<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ClaimProcessingCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ClaimProcessingCodes
 *
 * @description Code type wrapper for ClaimProcessingCodes enum
 */
class ClaimProcessingCodesType extends CodePrimitive
{
    public function __construct(
        /** @param ClaimProcessingCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
