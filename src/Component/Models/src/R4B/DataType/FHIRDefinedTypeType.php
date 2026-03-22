<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRDefinedType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type FHIRDefinedType
 *
 * @description Code type wrapper for FHIRDefinedType enum
 */
class FHIRDefinedTypeType extends CodePrimitive
{
    public function __construct(
        /** @param FHIRDefinedType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
