<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CatalogEntryRelationType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CatalogEntryRelationType
 *
 * @description Code type wrapper for CatalogEntryRelationType enum
 */
class CatalogEntryRelationTypeType extends CodePrimitive
{
    public function __construct(
        /** @param CatalogEntryRelationType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
