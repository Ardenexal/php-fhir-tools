<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapAttributeType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapAttributeType
 *
 * @description Code type wrapper for FHIRConceptMapAttributeType enum
 */
class FHIRFHIRConceptMapAttributeTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConceptMapAttributeType|string|null $value The code value */
        public FHIRFHIRConceptMapAttributeType|string|null $value = null,
    ) {
    }
}
