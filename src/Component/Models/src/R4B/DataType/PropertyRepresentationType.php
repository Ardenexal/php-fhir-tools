<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\PropertyRepresentation;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type PropertyRepresentation
 *
 * @description Code type wrapper for PropertyRepresentation enum
 */
class PropertyRepresentationType extends CodePrimitive
{
    public function __construct(
        /** @param PropertyRepresentation|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
