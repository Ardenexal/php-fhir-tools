<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\LinkRelationTypes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type LinkRelationTypes
 *
 * @description Code type wrapper for LinkRelationTypes enum
 */
class LinkRelationTypesType extends CodePrimitive
{
    public function __construct(
        /** @param LinkRelationTypes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
