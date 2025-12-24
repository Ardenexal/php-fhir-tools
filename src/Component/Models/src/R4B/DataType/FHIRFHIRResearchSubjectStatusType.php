<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResearchSubjectStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRResearchSubjectStatus
 *
 * @description Code type wrapper for FHIRResearchSubjectStatus enum
 */
class FHIRFHIRResearchSubjectStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResearchSubjectStatus|string|null $value The code value */
        public FHIRFHIRResearchSubjectStatus|string|null $value = null,
    ) {
    }
}
