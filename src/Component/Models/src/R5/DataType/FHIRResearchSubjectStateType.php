<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRResearchSubjectState;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchSubjectState
 *
 * @description Code type wrapper for FHIRResearchSubjectState enum
 */
class FHIRResearchSubjectStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResearchSubjectState|string|null $value The code value */
        public FHIRResearchSubjectState|string|null $value = null,
    ) {
    }
}
