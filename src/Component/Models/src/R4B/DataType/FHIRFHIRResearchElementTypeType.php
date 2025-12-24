<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResearchElementType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRResearchElementType
 *
 * @description Code type wrapper for FHIRResearchElementType enum
 */
class FHIRFHIRResearchElementTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResearchElementType|string|null $value The code value */
        public FHIRFHIRResearchElementType|string|null $value = null,
    ) {
    }
}
