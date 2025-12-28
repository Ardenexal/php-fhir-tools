<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionRelationshipType
 *
 * @description Code type wrapper for FHIRActionRelationshipType enum
 */
class FHIRFHIRActionRelationshipTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionRelationshipType|string|null $value The code value */
        public FHIRFHIRActionRelationshipType|string|null $value = null,
    ) {
    }
}
