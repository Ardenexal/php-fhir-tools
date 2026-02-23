<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\DocumentRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type DocumentRelationshipType
 *
 * @description Code type wrapper for DocumentRelationshipType enum
 */
class DocumentRelationshipTypeType extends CodePrimitive
{
    public function __construct(
        /** @param DocumentRelationshipType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
