<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapRelationship;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapRelationship
 *
 * @description Code type wrapper for FHIRConceptMapRelationship enum
 */
class FHIRFHIRConceptMapRelationshipType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConceptMapRelationship|string|null $value The code value */
        public FHIRFHIRConceptMapRelationship|string|null $value = null,
    ) {
    }
}
