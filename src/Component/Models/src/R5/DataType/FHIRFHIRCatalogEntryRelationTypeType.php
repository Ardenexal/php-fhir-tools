<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCatalogEntryRelationType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCatalogEntryRelationType
 *
 * @description Code type wrapper for FHIRCatalogEntryRelationType enum
 */
class FHIRFHIRCatalogEntryRelationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCatalogEntryRelationType|string|null $value The code value */
        public FHIRFHIRCatalogEntryRelationType|string|null $value = null,
    ) {
    }
}
