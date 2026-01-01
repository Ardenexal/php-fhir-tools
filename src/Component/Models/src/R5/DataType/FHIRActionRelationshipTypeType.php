<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRActionRelationshipType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
