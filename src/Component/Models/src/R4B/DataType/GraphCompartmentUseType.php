<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\GraphCompartmentUse;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type GraphCompartmentUse
 *
 * @description Code type wrapper for GraphCompartmentUse enum
 */
class GraphCompartmentUseType extends CodePrimitive
{
    public function __construct(
        /** @param GraphCompartmentUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
