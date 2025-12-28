<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCatalogEntryRelationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCatalogEntryRelationType
 *
 * @description Code type wrapper for FHIRCatalogEntryRelationType enum
 */
class FHIRCatalogEntryRelationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCatalogEntryRelationType|string|null $value The code value */
        public FHIRCatalogEntryRelationType|string|null $value = null,
    ) {
    }
}
