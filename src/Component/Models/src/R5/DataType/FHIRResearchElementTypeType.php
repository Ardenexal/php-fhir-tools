<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchElementType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchElementType
 *
 * @description Code type wrapper for FHIRResearchElementType enum
 */
class FHIRResearchElementTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResearchElementType|string|null $value The code value */
        public FHIRResearchElementType|string|null $value = null,
    ) {
    }
}
