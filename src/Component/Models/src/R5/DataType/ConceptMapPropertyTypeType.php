<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ConceptMapPropertyType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConceptMapPropertyType
 *
 * @description Code type wrapper for ConceptMapPropertyType enum
 */
class ConceptMapPropertyTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ConceptMapPropertyType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
