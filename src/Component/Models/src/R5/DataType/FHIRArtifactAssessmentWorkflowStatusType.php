<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentWorkflowStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentWorkflowStatus
 *
 * @description Code type wrapper for FHIRArtifactAssessmentWorkflowStatus enum
 */
class FHIRArtifactAssessmentWorkflowStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRArtifactAssessmentWorkflowStatus|string|null $value The code value */
        public FHIRArtifactAssessmentWorkflowStatus|string|null $value = null,
    ) {
    }
}
