<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionRelationshipType
 *
 * @description Code type wrapper for FHIRActionRelationshipType enum
 */
class FHIRActionRelationshipTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionRelationshipType|string|null $value The code value */
        public FHIRActionRelationshipType|string|null $value = null,
    ) {
    }
}
