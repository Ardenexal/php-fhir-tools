<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResearchStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchStudyStatus
 *
 * @description Code type wrapper for FHIRResearchStudyStatus enum
 */
class FHIRFHIRResearchStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResearchStudyStatus|string|null $value The code value */
        public FHIRFHIRResearchStudyStatus|string|null $value = null,
    ) {
    }
}
