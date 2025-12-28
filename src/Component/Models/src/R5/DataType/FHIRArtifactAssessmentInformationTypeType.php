<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentInformationType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentInformationType
 *
 * @description Code type wrapper for FHIRArtifactAssessmentInformationType enum
 */
class FHIRArtifactAssessmentInformationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRArtifactAssessmentInformationType|string|null $value The code value */
        public FHIRArtifactAssessmentInformationType|string|null $value = null,
    ) {
    }
}
