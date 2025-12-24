<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRArtifactAssessmentWorkflowStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentWorkflowStatus
 *
 * @description Code type wrapper for FHIRArtifactAssessmentWorkflowStatus enum
 */
class FHIRFHIRArtifactAssessmentWorkflowStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRArtifactAssessmentWorkflowStatus|string|null $value The code value */
        public FHIRFHIRArtifactAssessmentWorkflowStatus|string|null $value = null,
    ) {
    }
}
