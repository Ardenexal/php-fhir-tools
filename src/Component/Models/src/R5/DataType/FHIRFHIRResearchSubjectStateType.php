<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRResearchSubjectState;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchSubjectState
 *
 * @description Code type wrapper for FHIRResearchSubjectState enum
 */
class FHIRFHIRResearchSubjectStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResearchSubjectState|string|null $value The code value */
        public FHIRFHIRResearchSubjectState|string|null $value = null,
    ) {
    }
}
