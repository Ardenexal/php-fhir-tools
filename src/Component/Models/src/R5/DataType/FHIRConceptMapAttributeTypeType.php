<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConceptMapAttributeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapAttributeType
 *
 * @description Code type wrapper for FHIRConceptMapAttributeType enum
 */
class FHIRConceptMapAttributeTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRConceptMapAttributeType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
