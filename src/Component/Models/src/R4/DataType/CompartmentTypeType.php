<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CompartmentType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CompartmentType
 *
 * @description Code type wrapper for CompartmentType enum
 */
class CompartmentTypeType extends CodePrimitive
{
    public function __construct(
        /** @param CompartmentType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
