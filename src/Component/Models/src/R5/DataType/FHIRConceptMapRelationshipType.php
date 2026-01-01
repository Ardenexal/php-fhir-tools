<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConceptMapRelationship;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapRelationship
 *
 * @description Code type wrapper for FHIRConceptMapRelationship enum
 */
class FHIRConceptMapRelationshipType extends FHIRCode
{
    public function __construct(
        /** @param FHIRConceptMapRelationship|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
