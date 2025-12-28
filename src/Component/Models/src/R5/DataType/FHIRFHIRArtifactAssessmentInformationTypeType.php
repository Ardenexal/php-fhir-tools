<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRArtifactAssessmentInformationType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentInformationType
 *
 * @description Code type wrapper for FHIRArtifactAssessmentInformationType enum
 */
class FHIRFHIRArtifactAssessmentInformationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRArtifactAssessmentInformationType|string|null $value The code value */
        public FHIRFHIRArtifactAssessmentInformationType|string|null $value = null,
    ) {
    }
}
