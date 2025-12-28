<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRArtifactAssessmentWorkflowStatus
 * @description Code type wrapper for FHIRArtifactAssessmentWorkflowStatus enum
 */
class FHIRArtifactAssessmentWorkflowStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentWorkflowStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentWorkflowStatus|string|null $value = null,
	) {
	}
}
