<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ActionRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionRelationshipType
 *
 * @description Code type wrapper for ActionRelationshipType enum
 */
class ActionRelationshipTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ActionRelationshipType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
