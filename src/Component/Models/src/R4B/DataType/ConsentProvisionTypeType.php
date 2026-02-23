<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ConsentProvisionType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConsentProvisionType
 *
 * @description Code type wrapper for ConsentProvisionType enum
 */
class ConsentProvisionTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ConsentProvisionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
