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
        /** @var FHIRConceptMapAttributeType|string|null $value The code value */
        public FHIRConceptMapAttributeType|string|null $value = null,
    ) {
    }
}
