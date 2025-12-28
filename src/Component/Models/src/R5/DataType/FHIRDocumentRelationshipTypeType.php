<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentRelationshipType
 *
 * @description Code type wrapper for FHIRDocumentRelationshipType enum
 */
class FHIRDocumentRelationshipTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDocumentRelationshipType|string|null $value The code value */
        public FHIRDocumentRelationshipType|string|null $value = null,
    ) {
    }
}
