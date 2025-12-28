<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchSubjectStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchSubjectStatus
 *
 * @description Code type wrapper for FHIRResearchSubjectStatus enum
 */
class FHIRResearchSubjectStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResearchSubjectStatus|string|null $value The code value */
        public FHIRResearchSubjectStatus|string|null $value = null,
    ) {
    }
}
