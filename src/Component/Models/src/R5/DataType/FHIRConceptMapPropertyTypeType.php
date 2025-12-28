<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConceptMapPropertyType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapPropertyType
 *
 * @description Code type wrapper for FHIRConceptMapPropertyType enum
 */
class FHIRConceptMapPropertyTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConceptMapPropertyType|string|null $value The code value */
        public FHIRConceptMapPropertyType|string|null $value = null,
    ) {
    }
}
