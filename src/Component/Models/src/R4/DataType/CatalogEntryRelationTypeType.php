<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CatalogEntryRelationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

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
