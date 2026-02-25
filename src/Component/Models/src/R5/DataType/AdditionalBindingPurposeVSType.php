<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AdditionalBindingPurposeVS;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AdditionalBindingPurposeVS
 *
 * @description Code type wrapper for AdditionalBindingPurposeVS enum
 */
class AdditionalBindingPurposeVSType extends CodePrimitive
{
    public function __construct(
        /** @param AdditionalBindingPurposeVS|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
