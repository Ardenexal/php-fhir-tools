<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapPropertyType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapPropertyType
 *
 * @description Code type wrapper for FHIRConceptMapPropertyType enum
 */
class FHIRFHIRConceptMapPropertyTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConceptMapPropertyType|string|null $value The code value */
        public FHIRFHIRConceptMapPropertyType|string|null $value = null,
    ) {
    }
}
