<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchStudyStatus
 *
 * @description Code type wrapper for FHIRResearchStudyStatus enum
 */
class FHIRResearchStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResearchStudyStatus|string|null $value The code value */
        public FHIRResearchStudyStatus|string|null $value = null,
    ) {
    }
}
