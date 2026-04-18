<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\Confidentiality;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type Confidentiality
 *
 * @description Code type wrapper for Confidentiality enum
 */
class ConfidentialityType extends CodePrimitive
{
    public function __construct(
        /** @param Confidentiality|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
