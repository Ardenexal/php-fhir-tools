<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTypes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type FHIRTypes
 *
 * @description Code type wrapper for FHIRTypes enum
 */
class FHIRTypesType extends CodePrimitive
{
    public function __construct(
        /** @param FHIRTypes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
