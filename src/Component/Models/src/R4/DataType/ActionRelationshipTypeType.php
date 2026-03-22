<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ActionRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

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
