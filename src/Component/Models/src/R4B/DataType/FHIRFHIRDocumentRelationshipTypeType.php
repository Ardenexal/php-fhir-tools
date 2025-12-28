<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentRelationshipType
 *
 * @description Code type wrapper for FHIRDocumentRelationshipType enum
 */
class FHIRFHIRDocumentRelationshipTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDocumentRelationshipType|string|null $value The code value */
        public FHIRFHIRDocumentRelationshipType|string|null $value = null,
    ) {
    }
}
